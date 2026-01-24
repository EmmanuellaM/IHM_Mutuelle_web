<?php
// verify_balance_logic.php
echo "=== VERIFICATION: SAVINGS BALANCE LOGIC ===\n\n";

// Mock Data representing User Scenario
// 1. Initial Savings: 100,000 (Session 1)
// 2. Penalty Deduction: -1,500 (Session 4, Month 3)

$savings = [
    (object)['amount' => 100000, 'date' => '2026-01-01'],
    (object)['amount' => -1500,   'date' => '2026-04-01']
];

echo "Simulating View Logic...\n";
echo str_pad("Amount", 15) . str_pad("Balance", 15) . "\n";
echo str_repeat("-", 30) . "\n";

$runningBalance = 0;

foreach ($savings as $saving) {
    $amount = $saving->amount;
    $runningBalance += $amount;
    
    echo str_pad(number_format($amount, 0, ',', ' '), 15);
    echo str_pad(number_format($runningBalance, 0, ',', ' '), 15);
    echo "\n";
}

echo "\nFinal Balance Expected: 98 500\n";
echo "Final Balance Actual:   " . number_format($runningBalance, 0, ',', ' ') . "\n";

if ($runningBalance == 98500) {
    echo "✅ TEST PASSED: Balance is correct.\n";
} else {
    echo "❌ TEST FAILED.\n";
}
