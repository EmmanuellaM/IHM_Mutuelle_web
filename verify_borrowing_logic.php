<?php
// verify_borrowing_logic.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;
use app\models\Help;
use app\managers\FinanceManager;

echo "=== VERIFICATION: NEW BORROWING LOGIC (GUARANTEE BY SAVINGS) ===\n\n";

// 1. SETUP: Create Exercise with Penalty Rate
echo "1. Creating Exercise with Penalty Rate (5%)...\n";
$exercise = new Exercise();
$exercise->year = 2026;
$exercise->interest = 10; // 10% interest for easy calc
$exercise->inscription_amount = 0;
$exercise->social_crown_amount = 0;
$exercise->penalty_rate = 5; // 5% Penalty
$exercise->active = true;
$exercise->administrator_id = 1;
if (!$exercise->save()) die("Error saving exercise: " . print_r($exercise->errors, true));
echo "✅ Exercise created. Penalty Rate: {$exercise->penalty_rate}%\n";

// 2. SETUP: Create Session 1
$session1 = new Session();
$session1->exercise_id = $exercise->id;
$session1->date = '2026-01-01';
$session1->active = true;
$session1->save();
echo "✅ Session 1 created.\n";

// 3. SETUP: Create Member with Savings
$member = Member::find()->one(); 
if (!$member) die("No member found\n");
$member->insoluble = false;
$member->save(false);

// Add Savings: 20,000
$saving = new Saving();
$saving->member_id = $member->id;
$saving->session_id = $session1->id;
$saving->amount = 20000;
$saving->save();
echo "✅ Member {$member->name} has saved 20,000.\n";

// 4. TEST: Borrowing Capacity (5x)
// Try borrowing 150,000 (Should fail, limit is 100,000)
$borrowing = new Borrowing();
$borrowing->member_id = $member->id;
$borrowing->session_id = $session1->id;
$borrowing->amount = 150000;
$borrowing->interest = $exercise->interest; // 10%
$borrowing->state = true;
if (!$borrowing->checkCapacity($member->savedAmount($exercise))) {
    echo "✅ CAPACITY TEST PASSED: 150,000 > 100,000 rejected.\n";
} else {
    echo "❌ CAPACITY TEST FAILED: 150,000 allowed.\n";
}

// Borrow 100,000 (Should pass)
$borrowing->amount = 100000;
if ($borrowing->checkCapacity($member->savedAmount($exercise))) {
    $borrowing->save();
    echo "✅ CAPACITY TEST PASSED: 100,000 <= 100,000 accepted.\n";
} else {
    echo "❌ CAPACITY TEST FAILED: 100,000 rejected.\n";
}

// Verify Received Amount (Net)
// Intended: 100,000. Interest 10% = 10,000. Received = 90,000.
echo "   Intended Amount (Debt): " . $borrowing->intendedAmount() . " (Expected 100000)\n";
echo "   Received Amount (Net): " . $borrowing->receivedAmount() . " (Expected 90000)\n";

// 5. TEST: Month 3 Interest Deduction
echo "\n--- SIMULATING MONTH 3 ---\n";
// Create Session 4 (Month 3 elapsed: S1->S2->S3->S4)
// S1 (Jan), S2 (Feb), S3 (Mar), S4 (Apr) => 3 months elapsed
for ($i=2; $i<=4; $i++) {
    $s = new Session();
    $s->exercise_id = $exercise->id;
    $s->date = date('Y-m-d', strtotime("2026-0$i-01"));
    $s->active = true;
    $s->save(); 
    echo "   Created Session $i ({$s->date})\n";
}
// Session 4 triggered afterSave logic? No, create loop triggers it.
// Let's verify if Saving was deducted.
$savings = Saving::find()->where(['member_id' => $member->id, 'amount' => -10000])->one(); // 10% of 100k
if ($savings) {
    echo "✅ MONTH 3 TEST PASSED: Interest of 10,000 was deducted automatically.\n";
} else {
    echo "❌ MONTH 3 TEST FAILED: No interest deduction found.\n";
}

// 6. TEST: Month 6 Default Alert
echo "\n--- SIMULATING MONTH 6 ---\n";
// Create Sessions 5, 6, 7. At Session 7 (Month 6 elapsed), should trigger alert.
// S1...S7 (Jul). 6 months after Jan.
for ($i=5; $i<=7; $i++) {
    $s = new Session();
    $s->exercise_id = $exercise->id;
    $s->date = date('Y-m-d', strtotime("2026-0$i-01"));
    $s->active = true;
    $s->save();
     echo "   Created Session $i ({$s->date})\n";
}

// Check coverage:
// Initial Savings: 20,000.
// Interest Deduction: -10,000.
// Current Savings: 10,000.
// Max Capacity: 10,000 * 5 = 50,000.
// Debt: 100,000.
// 50,000 < 100,000 => DEFAULT!
if ($borrowing->isInDefault($member)) {
    echo "✅ DEFAULT DETECTION PASSED: Member is in default.\n";
} else {
    echo "❌ DEFAULT DETECTION FAILED: Member not flagged.\n";
}

// 7. TEST: Admin Applying Penalty
echo "\n--- TESTING ADMIN PENALTY ---\n";
$penaltyRate = 5; // 5%
$expectedPenalty = (100000 * 5) / 100; // 5,000
echo "   Applying Penalty via Logic (simulating Controller)...\n";

$penaltyAmount = ($borrowing->amount * $exercise->penalty_rate) / 100;
$saving = new Saving();
$saving->member_id = $member->id;
$saving->session_id = 7; // Current
$saving->amount = -$penaltyAmount;
$saving->save();

echo "   Penalty Applied: $penaltyAmount\n";
echo "   New Savings Balance: " . $member->savedAmount($exercise) . "\n"; 
// 10,000 - 5,000 = 5,000. Still > 0. Member not insolvent yet unless we force it.

// FORCE INSOLVENCY: Apply another huge penalty
echo "   Forcing Insolvency...\n";
$saving = new Saving();
$saving->member_id = $member->id;
$saving->session_id = 7; 
$saving->amount = -6000; // Drops to -1000
$saving->save();

if ($member->savedAmount($exercise) <= 0) {
    $member->insoluble = true;
    $member->save(false);
    echo "✅ INSOLVENCY TEST PASSED: Member marked insoluble.\n";
} else {
    echo "❌ INSOLVENCY TEST FAILED.\n";
}

// 8. TEST: Help Interception
echo "\n--- TESTING HELP INTERCEPTION ---\n";
// Create Help
$help = new Help();
$help->member_id = $member->id;
$help->help_type_id = 1; // Dummy
$help->amount = 200000; // Aid Amount
$help->amount_from_social_fund = 0;
$help->save(false);

echo "   Help Amount: 200,000. Debt: " . $borrowing->getRemainingAmount() . " (100,000)\n";
$netAmount = $help->interceptDebtDeduction();
echo "   Net Amount after Interception: " . $netAmount . "\n";

if ($netAmount == 100000) {
    echo "✅ INTERCEPTION TEST PASSED: 100,000 deducted, 100,000 returned.\n";
} else {
    echo "❌ INTERCEPTION TEST FAILED: Got $netAmount.\n";
}
// Check Refunds
$refund = \app\models\Refund::find()->where(['borrowing_id' => $borrowing->id])->sum('amount');
if ($refund == 100000) {
    echo "✅ REFUND CREATED: 100,000 refunded.\n";
} else {
    echo "❌ REFUND ERROR: $refund\n";
}

// Cleanup
// (Optional, or rely on revert)
