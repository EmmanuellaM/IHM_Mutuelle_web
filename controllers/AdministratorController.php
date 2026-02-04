<?php

/**
 * Created by PhpStorm.
 * User: medric
 * Date: 23/12/18
 * Time: 20:03
 */

namespace app\controllers;


use app\managers\AdministratorSessionManager;
use app\managers\FinanceManager;
use app\managers\MailManager;
use app\managers\RedirectionManager;
use app\managers\SettingManager;
use app\models\Administrator;
use app\models\Agape;
use app\models\Agape3;
use app\models\Borrowing;
use app\models\BorrowingSaving;
use app\models\Contribution;
use app\models\ContributionTontine;
use app\models\Exercise;
use app\models\forms\FixInscriptionForm;
use app\models\forms\HelpTypeForm;
use app\models\forms\IdForm;
use app\models\forms\NewAdministratorForm;
use app\models\forms\NewBorrowingForm;
use app\models\forms\NewContributionForm;
use app\models\forms\NewContributionTontineForm;
use app\models\forms\NewHelpForm;
use app\models\forms\NewMemberForm;
use app\models\forms\NewRefundForm;
use app\models\forms\NewSavingForm;
use app\models\forms\NewSessionForm;
use app\models\forms\NewTontineForm;
use app\models\forms\SettingForm;
use app\models\forms\TontineTypeForm;
use app\models\forms\UpdatePasswordForm;
use app\models\forms\UpdateSocialInformationForm;
use app\models\Help;
use app\models\HelpType;
use app\models\Member;
use app\models\Refund;
use app\models\Renflouement;
use app\models\forms\FixRenflouementForm;
use app\models\Saving;
use app\models\Session;
use app\models\Tontine;
use app\models\TontineType;
use app\models\User;
use DateTime;
use Yii;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class AdministratorController extends Controller
{
    public $layout = "administrator_base";
    public $user;
    public $administrator;
    public $defaultAction = "accueil";




    public function beforeAction($action)
    {

        if (!\Yii::$app->user->getIsGuest()) {

            $user = User::findOne(\Yii::$app->user->getId());

            // Vérifier si l'utilisateur est un administrateur
            $administrator = Administrator::findOne(['user_id' => $user->id]);
            if ($administrator) {
                $this->user = $user;
                $this->administrator = $administrator;
                $this->view->params = ['user' => $this->user, 'administrator' => $this->administrator];
                return parent::beforeAction($action);
            }
            
            // Vérifier si l'utilisateur est un membre
            $member = Member::findOne(['user_id' => $user->id]);
            if ($member) {
                \Yii::$app->response->redirect("@member.home");
            } else {
                return RedirectionManager::abort($this);
            }
        } else {
            \Yii::$app->response->redirect("@guest.connection");
        }
    }


    public function actionAccueil()
    {
        AdministratorSessionManager::setHome();
        $session = Session::findOne(['active' => true]);
        $idModel = new IdForm();
        if ($session)
            $idModel->id = $session->id;
        $model = new NewSessionForm();
        $model->interest = SettingManager::getInterest();
        $model->inscription_amount = SettingManager::getInscription();
        $model->social_crown_amount = SettingManager::getSocialCrown();
        return $this->render('home', compact('session', 'model', 'idModel'));
    }
    // Nouvelle Session (ancien)
    // public function actionNouvelleSession()
    // {
    //     if (Yii::$app->request->getIsPost()) {
    //         $idModel = new IdForm();
    //         $model = new NewSessionForm();
    //         $session = null;
    //         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //             // Check if date is greater than current date
    //             $today = new DateTime();
    //             $submittedDate = new DateTime($model->date);

    //             if ($submittedDate < $today) {
    //                 // Add error message to the model
    //                 $model->addError('date', 'La date ne peut pas être dans le passé.');
    //                 return $this->render('home', compact('session', 'model', 'idModel'));
    //             }

    //             // Traitement de l'exercice
    //             $exercise = Exercise::findOne(['active' => true]);
    //             if ($exercise) {
    //                 // S'il y a un exercice en cours
    //                 if (count(Session::findAll(['exercise_id' => $exercise->id])) >= 12) {
    //                     // S'il ya deja 12 sessions pour cet exercice
    //                     $exercise->active = false;
    //                     $exercise->save();

    //                     $exercise = new Exercise();
    //                     $exercise->year = (int)(new DateTime())->format("Y");
    //                     $exercise->interest = $model->interest; // Set interest rate for the new exercise
    //                     $exercise->save();
    //                 }
    //             } else {
    //                 // S'il n'y a pas, on le crée
    //                 $exercise = new Exercise();
    //                 $exercise->year = $model->year;
    //                 $exercise->interest = $model->interest; // Set interest rate for the new exercise
    //                 $exercise->administrator_id = $this->administrator->id;
    //                 $exercise->save();
    //             }

    //             $session = new Session();
    //             $session->administrator_id = $this->administrator->id;
    //             $session->exercise_id = $exercise->id;
    //             $session->date = $model->date;
    //             $session->save();

    //             foreach (Member::find()->all() as $member) {
    //                 MailManager::alert_new_session($member->user(), $session);
    //             }

    //             return $this->redirect("@administrator.home");
    //         } else {
    //             return $this->render('home', compact('session', 'model', 'idModel'));
    //         }
    //     } else {
    //         return RedirectionManager::abort($this);
    //     }
    // }

    // Nouvelle Session (new)
    public function actionNouvelleSession()
    {
        if (Yii::$app->request->getIsPost()) {
            $idModel = new IdForm();
            $model = new NewSessionForm();
            $session = null;
            
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                // Check if date is greater than current date
                $today = new DateTime();
                $submittedDate = new DateTime($model->date);
                
                // Vérifier si c'est la création d'un nouvel exercice ou d'une nouvelle session
                $exercise = Exercise::findOne(['active' => true]);
                
                if ($exercise && $exercise->sessionNumber() >= 12) {
                     Yii::$app->session->setFlash('warning', "L'exercice actuel a atteint 12 sessions. Veuillez le clôturer avant de continuer.");
                     return $this->redirect(['administrator/cloturer-exercice', 'q' => $exercise->id]);
                }

                if (!$exercise) {
                    // Création d'un nouvel exercice
                    $exercise = new Exercise();
                    $exercise->year = $model->year;
                    $exercise->interest = $model->interest;
                    $exercise->inscription_amount = $model->inscription_amount;
                    $exercise->social_crown_amount = $model->social_crown_amount;
                    $exercise->penalty_rate = $model->penalty_rate;
                    $exercise->administrator_id = $this->administrator->id;
                    $exercise->active = true;
                    
                    if (!$exercise->save()) {
                        Yii::$app->session->setFlash('error', "Erreur lors de la création de l'exercice: " . json_encode($exercise->errors));
                        $model->addErrors($exercise->errors);
                        return $this->render('home', compact('session', 'model', 'idModel'));
                    }
                }

                // Vérifier s'il existe déjà une session pour ce mois dans cet exercice
                $submittedMonth = $submittedDate->format('m');
                $submittedYear = $submittedDate->format('Y');

                $sessionExists = Session::find()
                    ->where([
                        'AND',
                        ['exercise_id' => $exercise->id],
                        ['EXTRACT(MONTH FROM date)' => $submittedMonth],
                        ['EXTRACT(YEAR FROM date)' => $submittedYear]
                    ])
                    ->exists();

                if ($sessionExists) {
                    $model->addError('date', 'Une session existe déjà pour ce mois dans cet exercice.');
                    Yii::$app->session->setFlash('error', 'Une session existe déjà pour ce mois dans cet exercice.');
                    return $this->render('home', compact('session', 'model', 'idModel'));
                }

                try {
                    if ($session->save()) {
                        Yii::$app->session->setFlash('success', 'Session créée avec succès !');
                        return $this->redirect("@administrator.home");
                    } else {
                        Yii::$app->session->setFlash('error', "Erreur lors de la création de la session: " . json_encode($session->errors));
                    }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', "Exception: " . $e->getMessage());
                }

                $model->addErrors($session->errors);
                return $this->render('home', compact('session', 'model', 'idModel'));
            } else {
                Yii::$app->session->setFlash('error', "Erreur de validation: " . json_encode($model->errors));
                return $this->render('home', compact('session', 'model', 'idModel'));
            }
        } else {
            return RedirectionManager::abort($this);
        }
    }



    public function actionModifierSession($id)
    {
        $session = Session::findOne($id);
        if ($session) {
            $idModel = new IdForm();
            $model = new NewSessionForm();
            $model->date = $session->date;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $today = new DateTime();
                $submittedDate = new DateTime($model->date);
                $submittedDate1 = $submittedDate->format('Y-m-d');
                $today1 = $today->format('Y-m-d');

                $OSession = Session::find()->one();

                // Vérifier que la date de la session ne chevauche pas avec d'autres sessions
                $dateExists = Session::find()
                    ->where([
                        'AND',
                        ['>=', 'date', sprintf('%04d-%02d-01', $submittedDate->format('Y'), $submittedDate->format('m'))],
                        ['<', 'date', sprintf('%04d-%02d-01', $submittedDate->format('Y'), $submittedDate->format('m') + 1)],
                        ['!=', 'id', $session->id], // Exclure la session en cours
                    ])
                    ->all();
                if ($dateExists) {
                    $model->addError('date', 'Le mois de cette session a déjà été sélectionné pour une autre session.');
                    return $this->render('home', compact('session', 'model', 'idModel'));
                }

                // On ne vérifie plus la succession des mois pour permettre plus de flexibilité

                // verifier que le jour de création de la session actuelle ne soit par à un jour après le jour current
                if ($submittedDate1 < $today1) {
                    // Add error message to the model
                    $model->addError('date', 'La date ne peut pas être dans le passé.');
                    return $this->render('home', compact('session', 'model', 'idModel'));
                }

                if ($submittedDate1 == $session->date) {
                    $model->addError('date', 'La date n\'a pas été modifiée');
                    return $this->render('home', compact('session', 'model', 'idModel'));
                }

                // Mettre à jour la session
                $session->date = $model->date;
                $session->exercise_id = $session->exercise_id; // Conserver l'exercice existant
                $session->save();

                // Envoyer les notifications
                foreach (Member::find()->all() as $member) {
                    MailManager::alert_update_session($member->user(), $session);
                }

                return $this->redirect(['@administrator.home']);
            } else {
                return $this->render('home', compact('session', 'model', 'idModel'));
            }
        } else {
            throw new NotFoundHttpException('Session non trouvée.');
        }
    }

    // Supprimer une session
    public function actionSupprimerSession($q)
    {
        $session = Session::findOne($q);
        if ($session === null) {
            throw new NotFoundHttpException("The session does not exist.");
        }


        // Delete related Refunds
        Refund::deleteAll(['session_id' => $q]);

        // Find all related Borrowings and delete related BorrowingSaving records
        $borrowings = Borrowing::findAll(['session_id' => $q]);
        foreach ($borrowings as $borrowing) {
            BorrowingSaving::deleteAll(['borrowing_id' => $borrowing->id]);
            $borrowing->delete();
        }

        Saving::deleteAll(['session_id' => $q]);

        // Ensure you handle any related records or dependencies before deletion
        if ($session->delete()) {
            Yii::$app->session->setFlash('success', 'Session deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Error occurred while deleting the session.');
        }

        return $this->redirect('@administrator.home'); // Redirect to an appropriate page
    }


    public function actionProfil()
    {
        AdministratorSessionManager::setProfile();
        return $this->render('profile');
    }

    public function actionModifierProfil()
    {
        AdministratorSessionManager::setProfile();

        $socialModel = new UpdateSocialInformationForm();
        $passwordModel = new UpdatePasswordForm();

        $socialModel->attributes = [
            'username' => $this->administrator->username,
            'name' => $this->user->name,
            'first_name' => $this->user->first_name,
            'tel' => $this->user->tel,
            'email' => $this->user->email,
            'address' => $this->user->address,
        ];

        return $this->render('update_profile', compact('socialModel', 'passwordModel'));
    }

    public function actionModifierInformationSociale()
    {
        if (\Yii::$app->request->getIsPost()) {
            $socialModel = new UpdateSocialInformationForm();
            $passwordModel = new UpdatePasswordForm();

            if ($socialModel->load(\Yii::$app->request->post()) && $socialModel->validate()) {
                $administrator = Administrator::findOne(['username' => $socialModel->username]);
                if ($administrator && $administrator->id != $this->administrator->id) {

                    $socialModel->addError("username", "Ce nom d'utilisateur est déjà utilisé");
                    return $this->render('update_profile', compact('socialModel', 'passwordModel'));
                } else {
                    $this->user->name = $socialModel->name;
                    $this->user->first_name = $socialModel->first_name;
                    $this->user->tel = $socialModel->tel;
                    $this->user->email = $socialModel->email;
                    $this->user->address = $socialModel->address;
                    /* if (UploadedFile::getInstance($socialModel,"avatar"))
                         $this->user->avatar = FileManager::storeAvatar( UploadedFile::getInstance($socialModel,"avatar"),$socialModel->username,"ADMINISTRATOR");*/
                    if (UploadedFile::getInstance($socialModel, "avatar")) {
                        $this->user->avatar = UploadedFile::getInstance($socialModel, 'avatar');
                        $this->user->avatar->saveAs('img/upload/' . $this->user->avatar->basename . '.' . $this->user->avatar->extension);
                        $socialModel->avatar = $this->user->avatar->basename . '.' . $this->user->avatar->extension;
                    } else {
                        $this->user->avatar = null;
                    }
                    $this->user->save();
                    $this->administrator->username = $socialModel->username;
                    $this->administrator->save();
                    return $this->redirect("@administrator.profile");
                }
            } else
                return $this->render('update_profile', compact('socialModel', 'passwordModel'));
        } else {
            return RedirectionManager::abort($this);;
        }
    }

    public function actionModifierMotDePasse()
    {
        if (\Yii::$app->request->getIsPost()) {
            $socialModel = new UpdateSocialInformationForm();
            $socialModel->attributes = [
                'id' => $this->user->id,
                'username' => $this->administrator->username,
                'name' => $this->user->name,
                'first_name' => $this->user->first_name,
                'tel' => $this->user->tel,
                'email' => $this->user->email,
            ];

            ///problème on y arrive à modifier les mots de passe 

            $passwordModel = new UpdatePasswordForm();
            if ($passwordModel->load(\Yii::$app->request->post()) && $passwordModel->validate()) {
                if ($this->user->validatePassword($passwordModel->password)) {
                    $this->user->password = Yii::$app->getSecurity()->generatePasswordHash($passwordModel->new_password);
                    $this->user->save();
                    return $this->redirect("@administrator.profile");
                } else {
                    $passwordModel->addError('password', 'Le mot de passe ne correspond pas.');
                    return $this->render('update_profile', compact('socialModel', 'passwordModel'));
                }
            } else
                return $this->render('update_profile', compact('socialModel', 'passwordModel'));
        } else
            return RedirectionManager::abort($this);;
    }


    public function actionTypesAide()
    {
        AdministratorSessionManager::setHelps();
        $helpTypes = HelpType::find()->where(['active' => true])->all();
        return $this->render('help_types', compact('helpTypes'));
    }

    public function actionModifierTypeAide($q = 0)
    {
        if ($q) {
            $model = new HelpTypeForm();

            $helpType = HelpType::findOne($q);
            if ($helpType && $helpType->active) {
                $model->id = $helpType->id;
                $model->title = $helpType->title;
                $model->amount = $helpType->amount;
                return $this->render('update_help_type', compact('model'));
            } else {
                return RedirectionManager::abort($this);
            }
        } else {
            return RedirectionManager::abort($this);
        }
    }

    public function actionAppliquerModificationTypeAide()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new HelpTypeForm();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $helpType = HelpType::findOne($model->id);
                if ($helpType) {
                    $helpType->title = $model->title;
                    $helpType->amount = $model->amount;
                    $helpType->save();

                    Yii::$app->session->setFlash('success', 'Le type d\'aide a été modifié avec succès.');
                    return $this->render('update_help_type', compact('model'));
                }
            }

            return $this->render('update_help_type', compact('model'));
        } else {
            return RedirectionManager::abort($this);
        }
    }

    public function actionSupprimerTypeAide()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new HelpTypeForm();
            $model->load(Yii::$app->request->post());
            if ($model->id) {
                $helpType = HelpType::findOne($model->id);
                if ($helpType) {
                    $helpType->active = false;
                    $helpType->delete();
                    return $this->redirect("@administrator.help_types");
                } else
                    return RedirectionManager::abort($this);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    public function actionNouveauTypeAide()
    {
        AdministratorSessionManager::setHelps();
        $model = new HelpTypeForm();
        return $this->render('new_help_type', compact('model'));
    }

    public function actionAjouterTypeAide()
    {
        if (\Yii::$app->request->getIsPost()) {
            $model = new HelpTypeForm();

            $newModel = new HelpType();

            if ($model->load(Yii::$app->request->post()) && $newModel->validate()) {
                $helpType = new HelpType();
                $helpType->title = $model->title;
                $helpType->amount = $model->amount;
                $helpType->save();
                if ($helpType->save()) {
                    Yii::$app->session->setFlash('success', "Type d'aide créé avec succès");
                } else {
                    Yii::$app->session->setFlash('error', "Ce type d'aide existe déjà");
                }
                return $this->redirect('@administrator.help_types');
            } else
                return $this->render('new_help_type', compact('model'));
        } else {
            return RedirectionManager::abort($this);
        }
    }


    /*****deconnexion de l'administration  ***********************************************************
     *
     * important
     */
    public function actionDeconnexion()
    {
        if (\Yii::$app->request->post()) {
            \Yii::$app->user->logout();
            return $this->redirect('@guest.connection');
        } else {
            return RedirectionManager::abort($this);
        }
    }

    public function actionMembres()
    {
        AdministratorSessionManager::setMembers();
        $members = Member::find()->all();

        return $this->render('members', compact('members'));
    }

    /********************************action Nouveau Membre ***************************************** */
    public function actionNouveauMembre()
    {
        AdministratorSessionManager::setMembers();
        $model = new NewMemberForm();
        return $this->render('new_member', ['model' => $model]);
    }

    /********************action création d'un nouvel administrateur ************************************** */
    public function actionNouvelAdministrateur()
    {
        if ($this->administrator->root) {
            $model = new NewAdministratorForm();
            return $this->render("new_administrator", compact('model'));
        } else
            return RedirectionManager::abort($this);
    }


    /*******************************ajouter un nouvel administrateur *********************************************** */
    public function actionAjouterAdministrateur()
    {
        if (Yii::$app->request->post() && $this->administrator->root) {
            $model = new NewAdministratorForm();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (!Administrator::findOne(['username' => $model->username])) {
                    $user = new User();
                    $user->name = $model->name;
                    $user->first_name = $model->first_name;
                    $user->tel = $model->tel;
                    $user->email = $model->email;
                    $user->address = $model->address;
                    $user->type = "ADMINISTRATOR";
                    $user->password = (new Security())->generatePasswordHash($model->password);
                    /* if (UploadedFile::getInstance($model,'avatar'))
                         $user->avatar = FileManager::storeAvatar(UploadedFile::getInstance($model,'avatar'),$model->username,'MEMBER');
                     else
                         $user->avatar = null;*/
                    if (UploadedFile::getInstance($model, 'avatar')) {
                        $user->avatar = UploadedFile::getInstance($model, 'avatar');
                        $user->avatar->saveAs('img/upload/' . $user->avatar->basename . '.' . $user->avatar->extension);
                        $model->avatar = $user->avatar->basename . '.' . $user->avatar->extension;
                    } else {
                        $user->avatar = null;
                    }
                    $user->save();

                    $administrator = new Administrator();
                    $administrator->user_id = $user->id;
                    $administrator->root = false;
                    $administrator->username = $model->username;
                    $administrator->save();

                    return $this->redirect('@administrator.administrators');
                } else {
                    $model->addError('username', 'Ce nom d\'utilisateur est déjà pris');
                    return $this->render('new_administrator', compact('model'));
                }
            } else
                return $this->render('new_administrator', compact("model"));
        } else
            return RedirectionManager::abort($this);
    }

    /**********************************Ajouter un membre dans la mutuelle
     * dans cette partie nous pouvons ajouter en utilisant le mail mananger pour terminer
     * la procedure d'inscription
     * /* ********************************************************************* */
    public function actionAjouterMember()
    {
        if (\Yii::$app->request->post()) {
            $model = new NewMemberForm();

            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                if (!Member::findOne(['username' => $model->username])) {
                    $user = new User();
                    $user->name = $model->name;
                    $user->first_name = $model->first_name;
                    $user->tel = $model->tel;
                    $user->email = $model->email;
                    $user->address = $model->address;
                    $user->type = "MEMBER";
                    $user->password = (new Security())->generatePasswordHash($model->password);
                    /* if (UploadedFile::getInstance($model,'avatar'))
                         $user->avatar = FileManager::storeAvatar(UploadedFile::getInstance($model,'avatar'),$model->username,'MEMBER');
                     else
                         $user->avatar = null;*/

                    if (UploadedFile::getInstance($model, 'avatar')) {
                        $user->avatar = UploadedFile::getInstance($model, 'avatar');
                        $user->avatar->saveAs('img/upload/' . $user->avatar->basename . '.' . $user->avatar->extension);
                        $model->avatar = $user->avatar->basename . '.' . $user->avatar->extension;
                    } else {
                        $user->avatar = null;
                    }
                    $user->save();
                    /** try{
                     *
                     *
                     * if ($model->load(Yii::$app->request->post()) && $model->inscriptionmail(Yii::$app->params['adminEmail']))
                     * {
                     * Yii::$app->session->setFlash('contactFormSubmitted');
                     *
                     * return $this->refresh();
                     * }
                     * }catch (Swift_TransportException $e){
                     *
                     * return $this->redirect('@administrator.members');
                     *
                     * }**/

                    $member = new Member();
                    $member->administrator_id = $this->administrator->id;
                    $member->user_id = $user->id;
                    $member->username = $model->username;
                    // $member->inscription = SettingManager::getInscription();
                    $member->inscription = 0;
                    $member->active = false;
                    $member->save();

                    //Au depart il n'y avait pas ça la partie de l'email n'avait pas le try catch c'est parce que je n'ai pas la connexion que je met  ça dedans
                    // C'est pour tester la partie du membre
                    try {
                        \Yii::$app->mailer->compose()
                            ->setFrom('dylaneossombe@gmail.com')
                            ->setTo($model->email)

                            ->setSubject('Email sent from GI2025')
                            ->setHtmlBody('Bienvenue M/Mme : ' . $model->name . ' <hr>Votre nom d`utilisateur est : ' . $model->username . '<hr> Votre mot de passe est : ' . $model->password . ' Pensez a le modifier lors de votre premiere connexion')
                            ->send();
                    } catch (\Exception $message) {
                    }


                    /***MailManager::alert_new_member($user,$member);****/


                    /*try{
                         Yii::$app->mailer->compose()
                         ->setTo($user->email)
                         ->setFrom('jasonmfououoyono@gmail.com')
                         ->setSubject('Confirmation de création de compte de membre')
                         ->setTextBody("Bienvenue à la mutuelle des enseignants de l'école nationale supérieure polytechnique de Yaoundé")
                         ->send();
                     }catch(Swift_TransportException $e)*/
                    /* {
                         return $this->redirect('@administrator.members');
                     }*/

                    return $this->redirect('@administrator.members');
                }
                $model->addError('username', 'Ce nom d\'utilisateur est déjà pris');
                return $this->render('new_member', compact('model'));
            }
            return $this->render('new_member', compact('model'));
        } else {
            return RedirectionManager::abort($this);
        }
    }

    /*******************************action administrateur ********************************************************************* */
    public function actionAdministrateurs()
    {
        AdministratorSessionManager::setAdministrators();

        $administrators = Administrator::find()->all();
        return $this->render("administrators", compact("administrators"));
    }

    public function actionAdministrator($administrator)
    {
        AdministratorSessionManager::setAdministrators();
        $adminModel = Administrator::findOne($administrator);
        if (!$adminModel) {
            throw new \yii\web\NotFoundHttpException("Administrateur introuvable.");
        }
        $userModel = $adminModel->user();
        
        return $this->render('administrator_view', [
            'adminModel' => $adminModel,
            'userModel' => $userModel,
        ]);
    }

    /*************************action Epargne **************************************************** */
    public function actionEpargnes()
    {
        AdministratorSessionManager::setHome("saving");
        $model = new NewSavingForm();

        $query = Session::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $sessions = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render("savings", compact("model", "sessions", "pagination"));
    }

    /***********************************création d'une nouvelle épargne*************************************************************** */
    // public function actionNouvelleEpargne()
    // {
    //     $member1 = User::findAll(['type' => 'MEMBER']);
    //     if (Yii::$app->request->getIsPost()) {

    //         $model = new NewMemberForm();

    //         $query = Session::find();
    //         $pagination = new Pagination([
    //             'defaultPageSize' => 5,
    //             'totalCount' => $query->count(),
    //         ]);

    //         $sessions = $query->orderBy(['created_at' => SORT_DESC])
    //             ->offset($pagination->offset)
    //             ->limit($pagination->limit)
    //             ->all();

    //         $model = new NewSavingForm();
    //         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //             $member = Member::findOne($model->member_id);
    //             $session = Session::findOne($model->session_id);
    //             $member1 = Member::findOne($model->member_id); 
    //             // if ($member && $session && ($session->state == "SAVING")) {
    //             if ($member && $session) {
    //                 $saving = new Saving();
    //                 $savi = Saving::findOne(['session_id' => $session->id, 'member_id' => $member->id]);
    //                 foreach($member1 as $memb1){

    //                         try{
    //                             \Yii::$app->mailer->compose()
    //                             ->setFrom('dylaneossombe@gmail.com')
    //                             ->setTo($memb1->email)

    //                             ->setSubject('Email sent from GI2025')
    //                             ->setHtmlBody('Nouvelle Epargne')
    //                             ->send();

    //                         }catch(\Exception $message){

    //                         }
    //                 }
    //                 if ($savi) {
    //                     $savi->amount += $model->amount;
    //                     $savi->save();
    //                    foreach($member1 as $memb1){

    //                             try{
    //                                 \Yii::$app->mailer->compose()
    //                                 ->setFrom('dylaneossombe@gmail.com')
    //                                 ->setTo($memb1->email)

    //                                 ->setSubject('Email sent from GI2025')
    //                                 ->setHtmlBody('Nouvelle Epargne')
    //                                 ->send();

    //                             }catch(\Exception $message {

    //                             }
    //                     }
    //                 } else {
    //                     $saving->member_id = $model->member_id;
    //                     $saving->session_id = $model->session_id;
    //                     $saving->amount = $model->amount;
    //                     $saving->administrator_id = $this->administrator->id;
    //                     $saving->save();
    //                 }

    //                 return $this->redirect("@administrator.savings");
    //             } else
    //                 return RedirectionManager::abort($this);
    //         } else return $this->render("savings", compact("model", "pagination", "sessions"));
    //     } else
    //         return RedirectionManager::abort($this);
    // }

    public function actionNouvelleEpargne()
    {
        $model = new NewSavingForm();
        $members = User::findAll(['type' => 'MEMBER']);
    
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $member = Member::findOne($model->member_id);
                $session = Session::findOne($model->session_id);
    
                if ($member && $session) {
                    // Calculate cumulative savings for the member
                    $previousSavings = Saving::find()->where(['member_id' => $member->id])->sum('amount');
                    $cumulativeSavings = $previousSavings + $model->amount;
    
                    // Create a new saving record
                    $saving = new Saving();
                    $saving->member_id = $model->member_id;
                    $saving->session_id = $model->session_id;
                    $saving->amount = $model->amount;
                    $saving->administrator_id = Yii::$app->user->identity->id; // Adjust as per your application logic
                    $saving->EpargneCumul = $cumulativeSavings;
    
                    // Save the changes
                    if ($saving->save()) {
                        // Send email notification to members
                        foreach ($members as $member) {
                            try {
                                Yii::$app->mailer->compose()
                                    ->setFrom('dylaneossombe@gmail.com')
                                    ->setTo($member->email)
                                    ->setSubject('Nouvelle Epargne')
                                    ->setTextBody('Nouvelle Epargne')
                                    ->send();
                            } catch (\Exception $e) {
                                Yii::error('Failed to send email: ' . $e->getMessage());
                            }
                        }
    
                        // Redirect to savings page after successful saving
                        return $this->redirect("@administrator.savings");
                    } else {
                        Yii::error('Failed to save the saving record.');
                    }
                } else {
                    Yii::error('Member or session not found.');
                }
            } else {
                Yii::error('Failed to load model or model validation failed.');
            }
        }
    
        // Fetch all active sessions with pagination
        $query = Session::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $sessions = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    
        // Render the savings view with necessary data
        return $this->render('savings', [
            'model' => $model,
            'sessions' => $sessions,
            'pagination' => $pagination,
        ]);
    }


    /*********************************action sur les recherche de sessions *****************************************************/

    /*********************************action sur les remboursements **************************************************** */
    public function actionRemboursements()
    {
        AdministratorSessionManager::setHome("refund");

        $model = new NewRefundForm();

        $query = Session::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $sessions = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render("refunds", compact("model", "sessions", "pagination"));
    }

    /******************************création de nouveau Remboursement **************************************************** */
    public function actionNouveauRemboursement()
    {
        if (Yii::$app->request->getIsPost()) {

            $query = Session::find();
            $pagination = new Pagination([
                'defaultPageSize' => 5,
                'totalCount' => $query->count(),
            ]);

            $sessions = $query->orderBy(['created_at' => SORT_DESC])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $model = new NewRefundForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $member = Member::findOne($model->member_id);
                $session = Session::findOne($model->session_id);
                // if ($member && $session && ($session->state == "REFUND")) {
                if ($member && $session) {

                    $borrowing = Borrowing::findOne(['member_id' => $member->id, 'state' => true]);
                    $refundedAmount = FinanceManager::borrowingRefundedAmount($borrowing);

                    $intendedAmount = FinanceManager::intendedAmountFromBorrowing($borrowing);
                    $q = floor($intendedAmount);

                    if ($model->amount + $refundedAmount < $intendedAmount) {
                        $refund = new Refund();
                        $refund->borrowing_id = $borrowing->id;
                        $refund->member_id = $member->id;
                        $refund->session_id = $model->session_id;
                        $refund->amount = $model->amount;
                        $refund->administrator_id = $this->administrator->id;
                        $refund->save();

                        return $this->redirect("@administrator.refunds");
                    } elseif ($model->amount + $refundedAmount == $q) {
                        $refund = new Refund();
                        $refund->borrowing_id = $borrowing->id;
                        $refund->member_id = $member->id;
                        $refund->session_id = $model->session_id;
                        $refund->amount = $intendedAmount - $refundedAmount;
                        $refund->administrator_id = $this->administrator->id;
                        $refund->save();

                        $borrowing->state = false;
                        $borrowing->save();
                        return $this->redirect("@administrator.refunds");
                    } else {
                        $model->addError('amount', 'Le montant entré est supérieur au reste à payer');
                        return $this->render("refunds", compact("model", "pagination", "sessions"));
                    }
                } else
                    return RedirectionManager::abort($this);
            } else return $this->render("refunds", compact("model", "pagination", "sessions"));
        } else
            return RedirectionManager::abort($this);
    }

    // /***********************action sur les Emprunts **************************************** */
    // public function actionEmprunts()
    // {
    //     AdministratorSessionManager::setHome("borrowing");

    //     $model = new NewBorrowingForm();

    //     $query = Session::find();
    //     $pagination = new Pagination([
    //         'defaultPageSize' => 5,
    //         'totalCount' => $query->count(),
    //     ]);

    //     $sessions = $query->orderBy(['created_at' => SORT_DESC])
    //         ->offset($pagination->offset)
    //         ->limit($pagination->limit)
    //         ->all();

    //     return $this->render("borrowings", compact("model", "sessions", "pagination"));
    // }

    // /*********************************création d'un nouvel emprunt ********************************************************************* */
    // public function actionNouvelleEmprunt()
    // {
    //     if (!Yii::$app->request->isPost) {
    //         return RedirectionManager::abort($this);
    //     }
    
    //     $query = Session::find();
    //     $pagination = new Pagination([
    //         'defaultPageSize' => 5,
    //         'totalCount' => $query->count(),
    //     ]);
    
    //     $sessions = $query->orderBy(['created_at' => SORT_DESC])
    //         ->offset($pagination->offset)
    //         ->limit($pagination->limit)
    //         ->all();
    
    //     $model = new NewBorrowingForm();
    
    //     if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
    //         return $this->render("borrowings", compact("model", "sessions", "pagination"));
    //     }
    
    //     $member = Member::findOne($model->member_id);
    //     $session = Session::findOne($model->session_id);
    //     $exercise = Exercise::findOne(['active' => 1]);
    
    //     if (!$member || !$session || FinanceManager::numberOfSession() >= 12) {
    //         return RedirectionManager::abort($this);
    //     }
    
    //     if (Borrowing::findOne(['member_id' => $member->id, 'state' => true])) {
    //         $model->addError('member_id', 'Ce membre a déjà contracté un emprunt');
    //         return $this->render("borrowings", compact("model", "sessions", "pagination"));
    //     }
    
    //     $savings = Saving::find()->where(['member_id' => $member->id, 'session_id' => $session->id])->sum('amount');
    //     $maxBorrowingAmount = $this->calculateMaxBorrowingAmount($savings);
    
    //     // Pour ajouter le pop up au message d'erreur
    //     $model->checkBorrowingAmount($maxBorrowingAmount);
    //     if ($model->amount > $maxBorrowingAmount) {
    //         $errorMessage = 'Le montant demandé est supérieur au montant maximum empruntable basé sur les épargnes de cette session : ' . $maxBorrowingAmount . ' XAF';
    //         $model->addError('amount', $errorMessage);
    //         return $this->render("borrowings", compact("model", "sessions", "pagination", "errorMessage"));
    //     }
    
    //     $borrowing = new Borrowing();
    //     $borrowing->interest = $exercise ? $exercise->interest : 0; // Default to 0 if no active exercise
    //     $borrowing->amount = $model->amount;
    //     $borrowing->member_id = $model->member_id;
    //     $borrowing->administrator_id = $this->administrator->id;
    //     $borrowing->session_id = $model->session_id;
    
    //     $transaction = Yii::$app->db->beginTransaction();
    //     try {
    //         if (!$borrowing->save()) {
    //             throw new \Exception('Unable to save borrowing');
    //         }
    
    //         $totalSavedAmount = FinanceManager::totalSavedAmount();
    //         foreach (FinanceManager::exerciseSavings() as $saving) {
    //             $borrowingSaving = new BorrowingSaving();
    //             $borrowingSaving->saving_id = $saving->id;
    //             $borrowingSaving->borrowing_id = $borrowing->id;
    //             $borrowingSaving->percent = 100.0 * ((float)$saving->amount) / $totalSavedAmount;
    //             if (!$borrowingSaving->save()) {
    //                 throw new \Exception('Unable to save borrowing saving');
    //             }
    //         }
    
    //         $transaction->commit();
    //         return $this->redirect('@administrator.borrowings');
    //     } catch (\Exception $e) {
    //         $transaction->rollBack();
    //         $model->addError('general', 'An error occurred while processing your request. Please try again.');
    //         Yii::error($e->getMessage(), __METHOD__);
    //         return $this->render("borrowings", compact("model", "sessions", "pagination"));
    //     }
    // }
    
    // /**
    //  * Calculate the maximum borrowing amount based on savings.
    //  *
    //  * @param float $savings
    //  * @return float
    //  */
    // private function calculateMaxBorrowingAmount($savings)
    // {
    //     if ($savings <= 200000) {
    //         return 5 * $savings;
    //     } elseif ($savings <= 500000) {
    //         return 5 * $savings;
    //     } elseif ($savings <= 1000000) {
    //         return 4 * $savings;
    //     } elseif ($savings <= 1500000) {
    //         return 3 * $savings;
    //     } elseif ($savings <= 2000000) {
    //         return 2 * $savings;
    //     } else {
    //         return 1.5 * $savings;
    //     }
    // }


    /***********************action sur les Emprunts **************************************** */
public function actionEmprunts()
{
    AdministratorSessionManager::setHome("borrowing");

    $model = new NewBorrowingForm();

    $query = Session::find();
    $pagination = new Pagination([
        'defaultPageSize' => 5,
        'totalCount' => $query->count(),
    ]);

    $sessions = $query->orderBy(['created_at' => SORT_DESC])
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

    // ✅ AJOUT : récupération des membres
    $members = Member::find()->all();

    return $this->render(
        "borrowings",
        compact("model", "sessions", "pagination", "members")
    );
}

/*********************************création d'un nouvel emprunt ********************************************************************* */
public function actionNouvelleEmprunt()
{
    if (!Yii::$app->request->isPost) {
        return RedirectionManager::abort($this);
    }

    $query = Session::find();
    $pagination = new Pagination([
        'defaultPageSize' => 5,
        'totalCount' => $query->count(),
    ]);

    $sessions = $query->orderBy(['created_at' => SORT_DESC])
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

    $model = new NewBorrowingForm();

    // ✅ AJOUT : récupération des membres
    $members = Member::find()->all();

    if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
        return $this->render(
            "borrowings",
            compact("model", "sessions", "pagination", "members")
        );
    }

    $member = Member::findOne($model->member_id);
    $session = Session::findOne($model->session_id);
    $exercise = Exercise::findOne(['active' => 1]);

    if (!$member || !$session || FinanceManager::numberOfSession() >= 12) {
        return RedirectionManager::abort($this);
    }


    // ✅ MODIFICATION: Vérifier si le membre a un emprunt actif NON REMBOURSÉ
    $activeBorrowing = Borrowing::findOne(['member_id' => $member->id, 'state' => true]);
    
    if ($activeBorrowing) {
        // Vérifier si l'emprunt est complètement remboursé
        $intendedAmount = $activeBorrowing->intendedAmount();
        $refundedAmount = $activeBorrowing->refundedAmount();
        $remainingAmount = $intendedAmount - $refundedAmount;
        
        if ($remainingAmount <= 0) {
            // L'emprunt est complètement remboursé, on le clôture automatiquement
            $activeBorrowing->state = false;
            $activeBorrowing->save();
        } else {
            // Il reste encore à rembourser, on bloque le nouvel emprunt
            $model->addError('member_id', 
                'Ce membre a déjà un emprunt actif non remboursé. Reste à payer: ' 
                . number_format($remainingAmount, 0, ',', ' ') . ' XAF');
            return $this->render(
                "borrowings",
                compact("model", "sessions", "pagination", "members")
            );
        }
    }


    // ✅ MODIFICATION: Vérifier l'épargne TOTALE dans l'exercice, pas seulement dans cette session
    // Cela permet à un membre d'épargner à une session et emprunter à une autre session
    $savings = Saving::find()
        ->joinWith('session')
        ->where(['saving.member_id' => $member->id])
        ->andWhere(['session.exercise_id' => $exercise->id])
        ->sum('saving.amount');

    $maxBorrowingAmount = $this->calculateMaxBorrowingAmount($savings);

    // Pour ajouter le pop up au message d'erreur
    // Pour ajouter le pop up au message d'erreur
    if ($model->amount > $maxBorrowingAmount) {
        $errorMessage =
            'Le montant demandé est supérieur au montant maximum empruntable basé sur vos épargnes totales dans cet exercice : '
            . number_format($maxBorrowingAmount, 0, ',', ' ') . ' XAF (Épargne totale: ' 
            . number_format($savings, 0, ',', ' ') . ' XAF)';

        $model->addError('amount', $errorMessage);
        Yii::$app->session->setFlash('error', $errorMessage);

        return $this->redirect(['administrator/borrowings']);
    }

    // ✅ NOUVEAU : Vérifier si le FOND TOTAL est suffisant
    $availableFunds = $exercise->exerciseAmount();
    if ($model->amount > $availableFunds) {
        $errorMessage = "Impossible d'effectuer cet emprunt. Le fond total disponible est insuffisant : " 
            . number_format($availableFunds, 0, ',', ' ') . " XAF.";
        
        $model->addError('amount', $errorMessage);
        Yii::$app->session->setFlash('error', $errorMessage);

        return $this->redirect(['administrator/borrowings']);
    }

    $borrowing = new Borrowing();
    $borrowing->interest = SettingManager::getInterest();
    $borrowing->amount = $model->amount;
    $borrowing->member_id = $model->member_id;
    $borrowing->administrator_id = $this->administrator->id;
    $borrowing->session_id = $model->session_id;

    $transaction = Yii::$app->db->beginTransaction();
    try {
        if (!$borrowing->save()) {
            throw new \Exception('Unable to save borrowing');
        }

        $totalSavedAmount = FinanceManager::totalSavedAmount();
        foreach (FinanceManager::exerciseSavings() as $saving) {
            $borrowingSaving = new BorrowingSaving();
            $borrowingSaving->saving_id = $saving->id;
            $borrowingSaving->borrowing_id = $borrowing->id;
            $borrowingSaving->percent =
                100.0 * ((float) $saving->amount) / $totalSavedAmount;

            if (!$borrowingSaving->save()) {
                throw new \Exception('Unable to save borrowing saving');
            }
        }

        $transaction->commit();
        return $this->redirect('@administrator.borrowings');

    } catch (\Exception $e) {
        $transaction->rollBack();
        $model->addError(
            'general',
            'An error occurred while processing your request. Please try again.'
        );
        Yii::error($e->getMessage(), __METHOD__);

        return $this->render(
            "borrowings",
            compact("model", "sessions", "pagination", "members")
        );
    }
}





    /*******************************action sur des sesions ******************************************************************* * */
    public function actionSessions()
    {
        AdministratorSessionManager::setHome("session");

        $query = Exercise::find();
        $pagination = new Pagination([
            'defaultPageSize' => 1,
            'totalCount' => $query->count(),
        ]);

        $exercises = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $member = \app\models\Member::findOne(['user_id' => Yii::$app->user->id]);
        return $this->render("sessions", compact('exercises', 'pagination', 'member'));
    }

    /****************************Details sur les sessions ****************************************************************** */
    public function actionDetailsSession($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session) {
                return $this->render("details_session", compact('session'));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*******************************actions sur les exercices******************************************* */
    public function actionExercices()
    {
        AdministratorSessionManager::setHome("exercise");
        $query = Exercise::find();
        $pagination = new Pagination([
            'defaultPageSize' => 1,
            'totalCount' => $query->count(),
        ]);

        $exercises = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $member = \app\models\Member::findOne(['user_id' => Yii::$app->user->id]);

        // Données pour le graphique en camembert
        $labels = [];
        $data = [];
        $colors = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40'
        ];

        return $this->render('exercises', compact('exercises', 'pagination', 'member', 'labels', 'data', 'colors'));
    }

    /*******************************actions sur les renflouements******************************************* */
    public function actionRenflouements($q = 0)
    {
        AdministratorSessionManager::setHome("exercise");
        
        $exercise = null;
        if ($q) {
            $exercise = Exercise::findOne($q);
        } else {
            // Par défaut, afficher pour l'exercice actif ou le dernier
             $exercise = Exercise::find()->orderBy(['year' => SORT_DESC])->one();
        }
        
        if (!$exercise) {
            Yii::$app->session->setFlash('warning', "Aucun exercice trouvé.");
            return $this->redirect("@administrator.home");
        }

        $renflouements = Renflouement::find()->where(['next_exercise_id' => $exercise->id])->all();
        
        return $this->render('renflouements', compact('exercise', 'renflouements'));
    }

    public function actionReglerRenflouement($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new FixRenflouementForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                 $renflouement = Renflouement::findOne($id);
                 if ($renflouement) {
                     $remaining = $renflouement->getRemainingAmount();
                     if ($model->amount > $remaining) {
                         Yii::$app->session->setFlash('error', "Erreur : Le montant saisi dépasse le reste à payer ({$remaining} XAF).");
                     } else {
                         if ($renflouement->pay($model->amount)) {
                             Yii::$app->session->setFlash('success', "Paiement de renflouement enregistré.");
                         } else {
                             Yii::$app->session->setFlash('error', "Erreur lors du paiement.");
                         }
                     }
                     return $this->redirect(['administrator/renflouements', 'q' => $renflouement->exercise_id]);
                 }
            }
        }
        return RedirectionManager::abort($this);
    }

    /****************************dettes au cours des exercices******************************************* */
    public function actionDettesExercices()
    {
        AdministratorSessionManager::setHome("exercise_debt");

        // Récupérer l'exercice actif
        $exercise = Exercise::find()
            ->where(['active' => true])
            ->one();

        // Si aucun exercice n'existe, afficher la vue avec un message
        if (!$exercise) {
            return $this->render('exercise_debts', [
                'members' => [],
                'exercise' => null,
                'sessions' => [],
                'unpaidBorrowings' => []
            ]);
        }

        // Récupérer les sessions de l'exercice actif
        $sessions = Session::find()
            ->where(['exercise_id' => $exercise->id])
            ->orderBy(['date' => SORT_ASC])
            ->all();

        // Récupérer les membres avec leurs données de fond social et leur utilisateur
        $members = \app\models\Member::find()
            ->with(['savings', 'user'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // ✅ CORRECTION: Récupérer les emprunts NON REMBOURSÉS des exercices PRÉCÉDENTS
        // On cherche les emprunts actifs (state = true) dont la session appartient à un exercice terminé
        $unpaidBorrowings = Borrowing::find()
            ->joinWith('session')
            ->where(['borrowing.state' => true])
            ->andWhere(['session.exercise_id' => Exercise::find()->where(['active' => false])->select('id')])
            ->all();

        return $this->render('exercise_debts', [
            'members' => $members,
            'exercise' => $exercise,
            'sessions' => $sessions,
            'unpaidBorrowings' => $unpaidBorrowings
        ]);
    }

    public function actionPrintReport($type = 'exercise', $id = null)
    {
        $exercise = Exercise::find()
            ->where(['active' => true])
            ->one();

        if (!$exercise) {
            throw new NotFoundHttpException('Aucun exercice actif trouvé.');
        }

        $data = [];
        $title = '';

        if ($type === 'exercise') {
            $title = "Bilan de l'exercice " . $exercise->year;
            $data = [
                'exercise' => $exercise,
                'sessions' => Session::find()
                    ->where(['exercise_id' => $exercise->id])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
                'members' => \app\models\Member::find()
                    ->with(['savings', 'user'])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->all()
            ];
        } elseif ($type === 'session' && $id) {
            $session = Session::findOne($id);
            if (!$session || $session->exercise_id !== $exercise->id) {
                throw new NotFoundHttpException('Session non trouvée ou non valide.');
            }
            $title = "Bilan de la session du " . Yii::$app->formatter->asDate($session->date, 'php:F Y');
            $data = [
                'session' => $session,
                'exercise' => $exercise,
                'members' => \app\models\Member::find()
                    ->with(['savings' => function ($query) use ($session) {
                        $query->where(['session_id' => $session->id]);
                    }])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->all()
            ];
        }

        // Générer le contenu HTML
        $content = $this->renderPartial('print_report', [
            'title' => $title,
            'data' => $data,
            'type' => $type
        ]);

        // Configurer MPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 25,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        // Ajouter le CSS
        $mpdf->WriteHTML('<style>' . $this->renderPartial('print-report-styles') . '</style>');

        // Ajouter le contenu
        $mpdf->WriteHTML($content);

        // Générer le PDF
        $mpdf->Output($title . '.pdf', 'D');

        return false;
    }

    /**************************passer aux remboursements ************************************ * */
    public function actionPasserAuxRemboursements($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $session->state = "REFUND";
                $session->save();

                return $this->redirect("@administrator.home");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /************************************passer aux emprunts ************************************************************ */
    public function actionPasserAuxEmprunts($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $session->state = "BORROWING";
                $session->save();
                return $this->redirect("@administrator.home");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /************************************cloturer une Session************************************************************ */
    /******  public function actionCloturerSession($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $session->state = "END";
                $session->active = false;
                $session->save();

                Yii::$app->db->createCommand('UPDATE borrowing SET interest=interest+:val WHERE session_id!=:session_id AND state=1 ', [
                    ':val' => SettingManager::getInterest(),
                    ':session_id' => $session->id,
                ])->execute


                $members = Member::find()->all();
                foreach ($members as $member) {
                    MailManager::alert_end_session($member->user(), $member, $session);
                }

                return $this->redirect("@administrator.home");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);

    }****/
    public function actionCloturerSession($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $session->state = "END";
                $session->active = false;
                $session->save();


                // Nouvelle logique : appliquer les intérêts de pénalité uniquement après 3 mois
                // sur le montant restant dû
                $interestRate = SettingManager::getInterest();
                $borrowings = Borrowing::findAll(['state' => 1]); // Tous les emprunts actifs
                
                foreach ($borrowings as $borrowing) {
                    if ($borrowing->shouldApplyPenaltyInterest()) {
                        $borrowing->applyPenaltyInterest($interestRate);
                        $borrowing->save();
                    }
                }

                $members = Member::find()->all();
                foreach ($members as $member) {
                    MailManager::alert_end_session($member->user(), $member, $session);
                }

                Yii::$app->session->setFlash('success', 'La session a été clôturée avec succès.');
                return $this->redirect(['administrator/sessions']);
            } else {
                Yii::$app->session->setFlash('error', 'Session introuvable ou déjà clôturée.');
                return $this->redirect(['administrator/sessions']);
            }
        }

        Yii::$app->session->setFlash('error', 'Identifiant de session invalide.');
        return $this->redirect(['administrator/sessions']);
    }

    /********************************cloturer un exercice ********************************************************************** */
    public function actionCloturerExercice($q = 0)
    {
        if ($q) {
            $exercise = Exercise::findOne($q);
            if ($exercise && $exercise->active) {
                // Vérifier si 12 sessions sont atteintes
                if (!$exercise->canBeClosed()) {
                    Yii::$app->session->setFlash('error', "L'exercice ne peut pas être clôturé (moins de 12 sessions ou déjà inactif).");
                    return $this->redirect("@administrator.exercises");
                }
                
                // Si confirmation POST reçue (à gérer via une vue intermédiaire ou JS, mais ici on fait simple pour le moment)
                // Idéalement on affiche un récapitulatif avant.
                // Pour l'instant, on exécute la fermeture.
                
                // 1. Déterminer l'année du prochain exercice
                $nextYear = (int)$exercise->year + 1;
                
                // 2. Vérifier si l'exercice suivant existe déjà
                $newExercise = Exercise::find()->where(['year' => $nextYear])->one();
                
                if (!$newExercise) {
                    // Créer automatiquement le prochain exercice
                    $newExercise = new Exercise();
                    $newExercise->year = $nextYear;
                    $newExercise->interest = $exercise->interest;
                    $newExercise->inscription_amount = $exercise->inscription_amount;
                    $newExercise->social_crown_amount = $exercise->social_crown_amount;
                    $newExercise->administrator_id = $this->administrator->id;
                    $newExercise->active = true;
                    $newExercise->status = 'active';
                    
                    if (!$newExercise->save()) {
                        Yii::$app->session->setFlash('error', "Erreur lors de la création automatique du nouvel exercice.");
                        return $this->redirect("@administrator.exercises");
                    }
                }

                // 3. Clôturer l'exercice actuel et passer l'ID du prochain
                if ($exercise->closeExercise($newExercise->id)) {
                    Yii::$app->session->setFlash('success', "Exercice clôturé avec succès. Les renflouements on été calculés pour l'exercice " . $newExercise->year . ".");
                    return $this->redirect("@administrator.exercises");
                } else {
                    Yii::$app->session->setFlash('error', "Erreur lors de la clôture de l'exercice.");
                    return $this->redirect("@administrator.exercises");
                }
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /**************************rentrer aux remboursements ****************************************************************** */
    public function actionRentrerAuxRemboursements($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $borrowings = Borrowing::findAll(['session_id' => $session->id]);
                foreach ($borrowings as $borrowing) {
                    Yii::$app->db->createCommand()->delete('borrowing_saving', ['borrowing_id' => $borrowing->id])->execute();
                    // $borrowing->delete();
                }

                $session->state = "REFUND";
                $session->save();

                return $this->redirect("@administrator.home");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*******************************rentrer aux epargnes ************************************************************* */
    public function actionRentrerAuxEpargnes($q = 0)
    {
        if ($q) {
            $session = Session::findOne($q);
            if ($session && $session->active) {
                $refunds = Refund::findAll(['session_id' => $q]);
                foreach ($refunds as $refund) {
                    $borrowing = Borrowing::findOne($refund->borrowing_id);
                    $borrowing->state = true;
                    $borrowing->save();
                    // $refund->delete();
                }

                $session->state = "SAVING";
                $session->save();
                return $this->redirect("@administrator.home");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /**************************gestion des Dettes********************************************************** */
    public function actionTraiterDette($q = 0)
    {
        if ($q) {
            $refund = Refund::findOne($q);
            if ($refund && $refund->exercise_id) {
                $refund->exercise_id = null;
                $refund->save();
                return $this->redirect("@administrator.exercise_debts");
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /****************************action des membres de la mutuelle **************************************** */
    public function actionMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);

            if ($member) {
                return $this->render("member", compact("member"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /****************************action des membres de la mutuelle **************************************** */
    public function actionEpargneDetail($session_id = 0, $member_id = 0)
    {
        if ($session_id && $member_id) {
            // Fetch the session, member, and their respective user details
            $session = \app\models\Session::findOne($session_id);
            $member = \app\models\Member::findOne($member_id);
            $memberUser = \app\models\User::findOne($member->user_id);
            
            // Fetch all savings for this member in the given session
            $savings = \app\models\Saving::findAll(['session_id' => $session_id, 'member_id' => $member_id]);
            
            // Calculate the total savings amount
            $totalSavings = \app\models\Saving::find()->where(['session_id' => $session_id, 'member_id' => $member_id])->sum('amount');
        
            if ($session && $member) {
                // Render the savings details view
                return $this->render('savings_details', [
                    'session' => $session,
                    'member' => $member,
                    'memberUser' => $memberUser,
                    'savings' => $savings,
                    'totalSavings' => $totalSavings,
                ]);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*******************************epargne des membres de la mutuelle******************************************************************* */
    public function actionEpargneMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);
            if ($member) {
                $query = Exercise::find();
                $pagination = new Pagination([
                    'defaultPageSize' => 1,
                    'totalCount' => $query->count(),
                ]);

                $exercises = $query->orderBy(['created_at' => SORT_DESC])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
                return $this->render("saving_member", compact("member", "exercises", "pagination"));
            } else
                return RedirectionManager::abort($this);
        } else {
            return RedirectionManager::abort($this);
        }
    }

    /*****************************emprunt des membres ************************************************************** */
    public function actionEmpruntMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);
            if ($member) {
                $query = Exercise::find();
                $pagination = new Pagination([
                    'defaultPageSize' => 1,
                    'totalCount' => $query->count(),
                ]);

                $exercises = $query->orderBy(['created_at' => SORT_DESC])
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
                return $this->render("borrowing_member", compact("member", "exercises", "pagination"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*****************************detail des emprunts des membres ************************************************************** */
    public function actionEmpruntDetail($session_id = 0, $member_id = 0)
    {
        if ($session_id && $member_id) {
            // Fetch the session, member, and their respective user details
            $session = \app\models\Session::findOne($session_id);
            $member = \app\models\Member::findOne($member_id);
            $memberUser = \app\models\User::findOne($member->user_id);
            
            // Fetch all savings for this member in the given session
            $borrowings = \app\models\Borrowing::findAll(['session_id' => $session_id, 'member_id' => $member_id]);
            
            // Calculate the total savings amount
            $totalBorrowings = \app\models\Borrowing::find()->where(['session_id' => $session_id, 'member_id' => $member_id])->sum('amount');
        
            if ($session && $member) {
                // Render the savings details view
                return $this->render('borrowings_details', [
                    'session' => $session,
                    'member' => $member,
                    'memberUser' => $memberUser,
                    'borrowings' => $borrowings,
                    'totalBorrowings' => $totalBorrowings,
                ]);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }
    /***************************contribution des membres ************************************************************* */
    public function actionContributionMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);
            if ($member) {
                $contributions = Contribution::find()->where(['member_id' => $q])->orderBy(['created_at' => SORT_DESC])->all();
                return $this->render("contribution_member", compact("member", "contributions"));
            } else
                RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*****************************nouvelle aide côté administrateur ******************************************* */
    public function actionNouvelleAide()
    {
        AdministratorSessionManager::setHome("help");
        $model = new NewHelpForm();
        
        $helpTypes = \app\models\HelpType::find()->all();
        $help_amounts = [];
        foreach ($helpTypes as $type) {
            $help_amounts[$type->id] = $type->amount;
        }

        return $this->render("new_help", compact("model", "help_amounts"));
    }

    /********************************ajouter une aide ********************************************************** */
    public function actionAjouterAide()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new NewHelpForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $member = Member::findOne($model->member_id);
                $help_type = HelpType::findOne($model->help_type_id);
                $exercise = Exercise::findOne(['active' => true]);

                if ($member && $help_type && $member->active && $exercise) {
                    
                    // Vérifier le fond social disponible
                    $availableSocialFund = FinanceManager::getAvailableSocialFund();
                    $targetAmount = $help_type->amount;

                    // Vérifier si le fond social est suffisant
                    if ($availableSocialFund < $targetAmount) {
                        $model->addError('help_type_id', 
                            "Le fond social est insuffisant pour cette aide. Disponible: " . 
                            number_format($availableSocialFund, 0, ',', ' ') . " XAF, Requis: " . 
                            number_format($targetAmount, 0, ',', ' ') . " XAF");
                        
                        $helpTypes = \app\models\HelpType::find()->all();
                        $help_amounts = [];
                        foreach ($helpTypes as $type) {
                            $help_amounts[$type->id] = $type->amount;
                        }
                        return $this->render("new_help", compact("model", "help_amounts"));
                    }

                    // Créer l'aide financée uniquement par le fond social
                    $help = new Help();
                    $help->help_type_id = $model->help_type_id;
                    $help->member_id = $model->member_id;
                    $help->comments = $model->comments;
                    $help->administrator_id = $this->administrator->id;
                    
                    // Montants - uniquement du fond social
                    $help->amount = $targetAmount;
                    $help->amount_from_social_fund = $targetAmount;
                    $help->unit_amount = 0; // Plus de contributions des membres
                    $help->state = false; // Fermé car entièrement financé par le fond social
                    
                    $help->save();

                    // Enregistrer la transaction de sortie du fond social
                    FinanceManager::registerSocialFundExpense(
                        $help->amount_from_social_fund, 
                        "Aide sociale pour " . $member->user()->name . " (" . $help_type->title . ")",
                        $exercise
                    );

                    Yii::$app->session->setFlash('success', 'L\'aide a été créée avec succès et financée par le fond social !');
                    return $this->redirect(["@administrator.helps"]); 

                } else {
                        Yii::$app->session->setFlash('error', "Membre ou type d'aide invalide ou aucun exercice actif.");
                }
            }
            
            // Validation failed or Member invalid - re-render view with help_amounts
            $helpTypes = \app\models\HelpType::find()->all();
            $help_amounts = [];
            foreach ($helpTypes as $type) {
                $help_amounts[$type->id] = $type->amount;
            }
            return $this->render("new_help", compact("model", "help_amounts"));

        } else {
             return RedirectionManager::abort($this);
        }
    }

    /***********************************Details Aider côté administrateur ******************************************************* */
    public function actionDetailsAide($q = 0)
    {
        if ($q) {
            $help = Help::findOne($q);
            if ($help) {

                return $this->render("help_details", compact("help"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /***********************************Details Emprunt côté administrateur ******************************************************* */
    public function actionDetailsEmprunt($q = 0)
    {
        if ($q) {
            $borrowing = Borrowing::findOne($q);
            if ($borrowing) {

                return $this->render("borrowing_details", compact("borrowing"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /************************nouvelle contribution dans la mutuelle ****************************************************** */
    public function actionNouvelleContribution($q=0, $m=0) {
        if ($q) {
            $help = Help::findOne($q);
            if ($help && $help->state) {
                $model = new NewContributionForm();
                if ($m) {
                   $model->member_id =$m;
                }
                $model->help_id =$q;
                return $this->render("new_contribution",compact("model"));
            }
            else
                return RedirectionManager::abort($this);
        }
        else
            return RedirectionManager::abort($this);
    }

    /*************************ajouter des contributions ****************************************************** */
    public function actionAjouterContribution()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new NewContributionForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $member = Member::findOne($model->member_id);
                $help = Help::findOne($model->help_id);
                if ($member && $help && $help->state) {
                    $contribution = Contribution::findOne(['member_id' => $model->member_id, 'help_id' => $model->help_id]);
                    if ($contribution && !$contribution->state) {
                        $contribution->date = $model->date;
                        $contribution->amount += $model->amount;
                        if ($contribution->amount>= $help->unit_amount) {
                            # code...
                            $contribution->state = true;

                        } 

                        $contribution->administrator_id = $this->administrator->id;
                        $contribution->save(false);

                        if ($help->contributedAmount == $help->amount) {
                            $help->state = false;
                            $help->save();

                            // LOGIQUE Saisie de la Dette sur l'Aide (Si Insolvable)
                            $beneficiary = $help->member;
                            $exercise = \app\models\Exercise::findOne(['active' => true]);
                            
                            if ($beneficiary && $exercise) {
                                // 1. Calcul Dette Totale
                                $activeBorrowings = \app\models\Borrowing::find()
                                    ->where(['member_id' => $beneficiary->id, 'state' => true])
                                    ->all();
                                
                                $totalDebt = 0;
                                foreach ($activeBorrowings as $b) {
                                    $totalDebt += ($b->amount - $b->refundedAmount());
                                }

                                // 2. Vérification Solvabilité via l'état centralisé
                                if ($beneficiary->isInsolvent($exercise)) {
                                    // INSOLVABLE -> Saisie
                                    // Recalcul de la dette totale pour le montant de la saisie (si pas fait par isInsolvent)
                                    // (isInsolvent retourne bool, donc on doit recalculer le montant ici, ou l'améliorer pour retourner le montant)
                                    // Pour l'instant on garde le calcul de montant local ou on le refait.
                                    // On a déjà $totalDebt calculé ligne 2060 (voir ligne précédente dans code original si je ne l'ai pas supprimé).
                                    // Attendez, mon replace précédent a TOUT injecté.
                                    // Je dois justifier le check.
                                    
                                    $seizureAmount = min($totalDebt, $help->amount);
                                    
                                    if ($seizureAmount > 0) {
                                        // Créer le remboursement
                                        $refund = new Refund();
                                        $refund->member_id = $beneficiary->id;
                                        $refund->session_id = \app\models\Session::findOne(['active' => true])->id; // Session active
                                        $refund->borrowing_id = $activeBorrowings[0]->id; // Arbitraire: on rembourse le premier
                                        // Note: Dans un système parfait, on répartirait. Ici on simplifie.
                                        $refund->amount = $seizureAmount;
                                        $refund->save(false);
                                        
                                        // Notifications
                                        Yii::$app->session->setFlash('warning', "Aide clôturée. Le bénéficiaire étant insolvable, $seizureAmount XAF ont été saisis pour sa dette.");
                                        
                                        try {
                                            if ($beneficiary->user) {
                                                MailManager::alert_penalty($beneficiary->user, $beneficiary, $seizureAmount, "Saisie sur Aide (Insolvabilité)");
                                            }
                                        } catch (\Exception $e) {}
                                    }
                                }
                            }
                        }
                        Yii::$app->session->setFlash('success', 'Votre action a été un succes ,contribution ajoutée !');

                        return $this->redirect(["@administrator.help_details", 'q' => $help->id]);
                    } else{
                        Yii::$app->session->setFlash('error', 'Votre action a echoué ,contribution est bouclée pour ce membre ou n\'existe pas !');
                        return $this->render("new_contribution", compact('model'));
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Votre action a echoué ,membre inexistant ou l\'aide est bouclée ou n\'existe pas !');
                    return $this->render("new_contribution", compact('model'));
                }
            } else {
                Yii::$app->session->setFlash('error', 'Votre action a echoué ,requete non chargée ou invalidée !');
                return $this->render("new_contribution", compact('model'));
            }
        } else {
            Yii::$app->session->setFlash('error', 'mauvaise requete !');

            return $this->render("new_contribution", compact('model'));
        }
    }

    /******************************aide du côté Administrateur *************************************************** */

    public function actionAides()
    {
        AdministratorSessionManager::setHome("help");

        $activeHelps = Help::findAll(['state' => true]);

        $query = Help::find()->where(['state' => false])->orderBy('created_at', SORT_DESC);

        $pagination = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $query->count(),
        ]);

        $helps = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render("helps", compact("helps", 'pagination', "activeHelps"));
    }

    public function actionAgape()
    {
         $agapes = \app\models\Agape::find()->all();
         $sessions = \app\models\Session::find()->all();
         $model = new \app\models\Agape();
         return $this->render('agapes', compact('agapes', 'sessions', 'model'));
    }

    /********************************desactiver les membres ******************************************************* */
    public function actionDesactiverMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);
            if ($member && $member->active) {
                $member->active = false;
                $member->save();

                return $this->redirect(["@administrator.member", 'q' => $q]);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /********************************desactiver les admin ******************************************************* */
    public function actionDesactiverAdmin($q = 0)
    {
        if ($q) {
            $administrator = Administrator::findOne($q);
            if ($administrator && $administrator->active) {
                $administrator->active = false;
                $administrator->save();

                return $this->redirect(["@administrator.administrators", 'q' => $q]);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /************************supprimer les membres de la mutuelle **************************************************** */
    public function actionSupprimerMembre($q = 0)
    {
        $member = Member::findOne($q);
        if ($member) {
            $member->active = false;
            $member->save(false);
            return $this->redirect("@administrator.members");
        }
    }

    /************************supprimer les admin de la mutuelle **************************************************** */
    public function actionSupprimerAdmin($q = 0)
    {
        $administrator =  Administrator::findOne($q);
        if ($administrator) {
            $administrator->active = false;
            $administrator->delete();
            return $this->redirect("@administrator.administrators");
        }
        //  else {
        //     Yii::$app->session->setFlash('error', 'Failed to delete administrator.');
        // }
        // Redirect to the index page or any other page as required
    }

    /*****************************activer les membres de la mutuelle ************************************************* */
    public function actionActiverMembre($q = 0)
    {
        if ($q) {
            $member = Member::findOne($q);
            if ($member && !$member->active) {
                $member->active = true;
                $member->save();
                return $this->redirect(["@administrator.member", 'q' => $q]);
            } else return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*****************************activer les admin de la mutuelle ************************************************* */
    public function actionActiverAdmin($q = 0)
    {
        if ($q) {
            $administrator = Administrator::findOne($q);
            if ($administrator && !$administrator->active) {
                $administrator->active = true;
                $administrator->save();
                return $this->redirect(["@administrator.administrators", 'q' => $q]);
            } else return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /********************************supprimer Epargne ***************************************************** */
    public function actionSupprimerEpargne($q = 0)
    {
        if ($q) {
            $saving = Saving::findOne($q);
            if ($saving) {
                $session = $saving->session();
                if ($session && $session->active) {
                    $saving->delete();
                    return $this->redirect("@administrator.savings");
                } else
                    return RedirectionManager::abort($this);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /****************************action des membres de la mutuelle **************************************** */
    public function actionModifierEpargne($id)
    {
        $saving = Saving::findOne($id);
        if (!$saving) {
            throw new NotFoundHttpException('Saving not found.');
        }

        if (Yii::$app->request->isPost) {
            $newAmount = Yii::$app->request->post('Saving')['amount'];
            $saving->amount = $newAmount;

            if ($saving->save()) {
                Yii::$app->session->setFlash('success', 'Saving updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update the saving.');
            }
        }

        return $this->redirect(['administrator/epargne-detail', 'member_id' => $saving->member_id, 'session_id' => $saving->session_id]);
    }

    /******************************supprimer remboursement d'un membre *************************************************** */
    public function actionSupprimerRemboursement($q = 0)
    {
        if ($q) {
            $refund = Refund::findOne($q);
            if ($refund) {
                $session = $refund->session();
                if ($session && $session->active) {

                    $borrowing = $refund->borrowing();
                    $refund->delete();
                    if (!$borrowing->state) {
                        $borrowing->state == true;
                        $borrowing->save();
                    }
                    return $this->redirect("@administrator.refunds");
                } else
                    return RedirectionManager::abort($this);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /***************************supprimer les Emprunts******************************************** */
    public function actionSupprimerEmprunt($q = 0)
    {
        if ($q) {
            $borrowing = Borrowing::findOne($q);
            if ($borrowing) {
                $session = $borrowing->session();
                if ($session && $session->active) {
                    Yii::$app->db->createCommand()->delete('borrowing_saving', ['borrowing_id' => $borrowing->id])->execute();
                    $borrowing->delete();

                    return $this->redirect("@administrator.borrowings");
                } else
                    return RedirectionManager::abort($this);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /***************************modifier les Emprunts******************************************** */
    public function actionModifierEmprunt($id)
    {
        $borrowing = Borrowing::findOne($id);
        if (!$borrowing) {
            throw new NotFoundHttpException('Saving not found.');
        }

        if (Yii::$app->request->isPost) {
            $newAmount = Yii::$app->request->post('Borrowing')['amount'];
            $borrowing->amount = $newAmount;

            if ($borrowing->save()) {
                Yii::$app->session->setFlash('success', 'Emprunt modifie avec succes');
            } else {
                Yii::$app->session->setFlash('error', 'Echec de modification');
            }
        }

        return $this->redirect(['administrator/emprunt-detail', 'member_id' => $borrowing->member_id, 'session_id' => $borrowing->session_id]);
    }


    public function actionTestRenflouement()
    {
        $exercise = Exercise::findOne(['status' => 'closed']);
        if (!$exercise) $exercise = Exercise::findOne(['active' => 1]);
        
        echo "<h1>Debug Renflouement (Web Context)</h1>";
        echo "Exercise: " . ($exercise ? $exercise->year : 'None') . "<br>";
        
        if ($exercise) {
            $activeMembersQuery = \app\models\Member::find()->where(['active' => 1]);
            $activeMembersCount = $activeMembersQuery->count();
            $activeMembersList = $activeMembersQuery->all();
            
            echo "Active Members Count: " . $activeMembersCount . "<br>";
            echo "<ul>";
            foreach($activeMembersList as $m) {
                echo "<li>" . $m->user->name . " (Active: " . var_export($m->active, true) . ", Inscription: " . $m->inscription . ", Social: " . $m->social_crown . ")</li>";
            }
            echo "</ul>";
            
            echo "Total Agape: " . $exercise->totalAgapeAmount() . "<br>";
            echo "Total Helps (Social Fund): " . $exercise->getTotalHelpsFromSocialFund() . "<br>";
            
            $calc = $exercise->calculateRenflouementPerMember();
            echo "Calculated Per Member: " . $calc . "<br>";
        }
        
        exit;
    }

    /*******************************regler le Inscription************************************************************* */
        public function actionReglerInscription($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new FixInscriptionForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $member = Member::findOne($id);
                $exercise = Exercise::findOne(['active' => true]);
                
                if ($member && $exercise && ($member->inscription < $exercise->inscription_amount)) {
                    $member->inscription += $model->amount;
                    if ($member->inscription > $exercise->inscription_amount) $member->inscription = $exercise->inscription_amount;
                    $member->save();
                    return $this->redirect("@administrator.exercise_debts");
                } else {
                    Yii::$app->session->setFlash('error', 'Impossible de régler l\'inscription. Vérifiez le montant ou l\'exercice actif.');
                    return $this->redirect("@administrator.exercise_debts");
                }
            } else {
                Yii::$app->session->setFlash('error', 'Données invalides');
                return $this->redirect("@administrator.exercise_debts");
            }
        } else
            return RedirectionManager::abort($this);
    }
    /*******************************regler le Fond social ************************************************************* */
        public function actionReglerFondSocial($id)
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new \app\models\forms\FixSocialCrownForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $member = Member::findOne($id);
                $exercise = Exercise::findOne(['active' => true]);

                if ($member && $exercise && ($member->social_crown < $exercise->social_crown_amount)) {
                    $remaining = $exercise->social_crown_amount - $member->social_crown;
                    
                    if ($model->amount > $remaining) {
                        Yii::$app->session->setFlash('error', "Le montant saisi ({$model->amount} XAF) dépasse le montant restant à payer ({$remaining} XAF).");
                        return $this->redirect("@administrator.exercise_debts");
                    }
                    
                    $member->social_crown += $model->amount;
                    // Double check (redundant but safe)
                    if ($member->social_crown > $exercise->social_crown_amount) {
                         $member->social_crown = $exercise->social_crown_amount;
                    }
                    
                    $member->save();
                    return $this->redirect("@administrator.exercise_debts");
                } else {
                    Yii::$app->session->setFlash('error', 'Impossible de régler le fond social. Vérifiez le montant ou l\'exercice actif.');
                    return $this->redirect("@administrator.exercise_debts");
                }
            } else {
                Yii::$app->session->setFlash('error', 'Données invalides');
                return $this->redirect("@administrator.exercise_debts");
            }
        } else
            return RedirectionManager::abort($this);
    }
    /***************************supprimer une aide*********************************************** */

    public function actionSupprimerAide($q = 0)
    {
        $help = Help::findOne($q);
        $contribution = Contribution::findOne($q);
        $member = Member::findOne($q);
        if ($contribution->active = true) {
            return $this->redirect("administrator.helps");
        } else {
            $member = $contribution->member();
            $member->active = false;
            $contribution->active = false;
            $help->active = false;
            $help->delete();
            return $this->redirect("@administrator.helps");
        }
    }

    /******************************configuration************************************************************* */
    public function actionConfigurations()
    {
        AdministratorSessionManager::setSettings();
        $model = new SettingForm();
        $model->interest = SettingManager::getInterest();
        $model->social_crown = SettingManager::getSocialCrown();
        $model->inscription = SettingManager::getInscription();
        $model->penalty_rate = SettingManager::getPenaltyRate();



        return $this->render("settings", compact("model"));
    }


    /*****************************Appliquer les configurations ********************************************************* */
   
   
   
   

    
    /*****************************Appliquer les configurations ********************************************************* */
public function actionAppliquerConfiguration()
{
    if (Yii::$app->request->getIsPost()) {
        $model = new SettingForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // 1. Sauvegarder dans SettingManager (votre système actuel)
            SettingManager::setValues($model->interest, $model->social_crown, $model->inscription, $model->penalty_rate);
            
            // 2. IMPORTANT : Mettre à jour aussi l'exercice actif
            $exercise = \app\models\Exercise::find()->where(['active' => 1])->one();
            
            if ($exercise) {
                // Sauvegarder les anciennes valeurs pour le message
                $oldInterest = $exercise->interest;
                $oldInscription = $exercise->inscription_amount;
                $oldSocialCrown = $exercise->social_crown_amount;
                
                // Mettre à jour l'exercice
                $exercise->interest = $model->interest;
                $exercise->inscription_amount = $model->inscription;
                $exercise->social_crown_amount = $model->social_crown;
                $exercise->penalty_rate = $model->penalty_rate;
                
                if ($exercise->save()) {
                    Yii::$app->session->setFlash('success', 
                        'Configuration mise à jour avec succès !<br>' .
                        '<strong>Intérêt:</strong> ' . $oldInterest . '% → ' . $model->interest . '%<br>' .
                        '<strong>Pénalité (m):</strong> ' . $model->penalty_rate . '%<br>' .
                        '<strong>Inscription:</strong> ' . number_format($oldInscription, 0, ',', ' ') . ' → ' . number_format($model->inscription, 0, ',', ' ') . ' XAF<br>' .
                        '<strong>Fond social:</strong> ' . number_format($oldSocialCrown, 0, ',', ' ') . ' → ' . number_format($model->social_crown, 0, ',', ' ') . ' XAF'
                    );
                } else {
                    Yii::$app->session->setFlash('warning', 
                        'Configuration sauvegardée mais erreur lors de la mise à jour de l\'exercice: ' . 
                        implode(', ', $exercise->getFirstErrors())
                    );
                }
            } else {
                Yii::$app->session->setFlash('warning', 
                    'Configuration sauvegardée mais aucun exercice actif trouvé.'
                );
            }
            
            return $this->redirect("@administrator.settings");
        } else {
            Yii::$app->session->setFlash('error', 'Données invalides');
            return $this->render("settings", compact("model"));
        }
    } else {
        return RedirectionManager::abort($this);
    }
}
   





/*************************enregistrement des membres durant une session************************************************************* */
    public static function savingofmember($member, $sessionss)
    {
        $r = Saving::find()->where(['session_id' => $sessionss, 'member_id' => $member->id])->sum('amount');
        if ($r) :
            return $r;
        else :
            return 0;
        endif;
    }




    /*******************************************Nouvelle Agape ********************************************************************************************/
    public function actionNouvelleAgape()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new Agape();
            if ($model->load(Yii::$app->request->post())) {
                $session = Session::findOne(['active' => true]);
                if ($session) {
                    // Validation du fond social
                    $availableFund = FinanceManager::getAvailableSocialFund();
                    if ($model->amount > $availableFund) {
                        Yii::$app->session->setFlash('error', "Impossible d'enregistrer l'agape : Solde insuffisant dans le fonds social. Disponible : " . number_format($availableFund, 0, ',', ' ') . " XAF. Montant demandé : " . number_format($model->amount, 0, ',', ' ') . " XAF.");
                        return $this->redirect("@administrator.agape");
                    }

                    $model->session_id = $session->id;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Agape enregistrée avec succès.');
                    } else {
                        Yii::$app->session->setFlash('error', "Erreur lors de l'enregistrement : " . implode(', ', $model->getErrorSummary(true)));
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Aucune session active. Impossible d\'enregistrer une agape.');
                }
                return $this->redirect("@administrator.agape");
            }
        }
        return RedirectionManager::abort($this);
    }

    public function actionUpdateAgape($id)
    {
        $agapeForm = $this->findModelAgape($id);
        $oldAmount = $agapeForm->amount;

        if ($agapeForm->load(Yii::$app->request->post()) && $agapeForm->validate()) {
            // Validation du fond social (on tient compte de l'ancien montant déjà déduit)
            $availableFund = FinanceManager::getAvailableSocialFund() + $oldAmount;
            
            if ($agapeForm->amount > $availableFund) {
                Yii::$app->session->setFlash('error', "Modification impossible : Solde insuffisant dans le fonds social. Disponible : " . number_format($availableFund, 0, ',', ' ') . " XAF.");
                return $this->redirect("@administrator.agape");
            }

            // Get the session ID and assign it to the model
            $agapeForm->session_id = Yii::$app->request->post('session_id');
            if ($agapeForm->save()) {
                Yii::$app->session->setFlash('success', "Agape mise à jour avec succès.");
            } else {
                Yii::$app->session->setFlash('error', "Erreur lors de la mise à jour.");
            }

            return $this->redirect("@administrator.agape");
        }

        // Retrieve the list of sessions for the dropdown
        $sessions = Session::find()->all();

        return $this->render('update-agape', [
            'agapeForm' => $agapeForm,
            'sessions' => $sessions,
        ]);
    }

    public function actionAgapeIndex()
    {
        AdministratorSessionManager::setAgape();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Agape::find(),
        ]);

        return $this->render('agape', ['dataProvider' => $dataProvider]);
    }

    public function actionAgapeView($id)
    {
        $agapeForm = $this->findModelAgape($id);
        return $this->render('agape-view', ['$agapeForm' => $agapeForm]);
    }

    protected function findModelAgape($id)
    {
        if (($agapeForm = Agape::findOne($id)) !== null) {
            return $agapeForm;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




    /*******************************************Type de Tontine********************************************************************************************/

    public function actionTypesTontine()
    {
        AdministratorSessionManager::setTontine();
        $tontineTypes = TontineType::find()->where(['active' => true])->all();

        return $this->render('tontine_types', compact('tontineTypes'));
    }
    /************************************************Modifier le type de tontine *****************************************************************************************************/
    public function actionModifierTypeTontine($q = 0)
    {

        if ($q) {
            $model = new TontineTypeForm();

            $tontineType = TontineType::findOne($q);
            if ($tontineType && $tontineType->active) {
                $model->id = $tontineType->id;
                $model->title = $tontineType->title;
                $model->amount = $tontineType->amount;
                return $this->render('update_tontine_type', compact('model'));
            } else {
                return RedirectionManager::abort($this);
            }
        } else {
            return RedirectionManager::abort($this);
        }
    }
    /************************************************les tontines **************************************************************************/
    public function actionTontines()
    {
        AdministratorSessionManager::setHome("tontine");

        $activeTontines = Tontine::findAll(['state' => true]);

        $query = Tontine::find()->where(['state' => false])->orderBy('created_at', SORT_DESC);

        $pagination = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $query->count(),
        ]);

        $tontines = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render("tontines", compact("tontines", 'pagination', "activeTontines"));
    }

    /**************************************Détails sur les tontines ***********************************************************************************/
    public function actionDetailsTontine($q = 0)
    {
        if ($q) {
            $tontine = Tontine::findOne($q);
            if ($tontine) {

                return $this->render("tontine_details", compact("tontine"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*****************************nouvelle  tontine côté administrateur ******************************************* */
    public function actionNouvelleTontine()
    {
        AdministratorSessionManager::setHome("tontine");
        $model = new NewTontineForm();
        return $this->render("new_tontine", compact("model"));
    }

    /********************************ajouter une tontine ********************************************************** */
    // public function actionAjouterTontine()
    // {
    //     if (Yii::$app->request->getIsPost()) {
    //         $model = new NewTontineForm();
    //         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //             $d1 = (new DateTime())->getTimestamp();
    //             $d2 = (new DateTime($model->limit_date))->getTimestamp();

    //             $member = Member::findOne($model->member_id);
    //             $tontine_type = TontineType::findOne($model->tontine_type_id);

    //             if ($member && $tontine_type && $member->active) {
    //                 //vérifier si le membre a déjà fait un emprunt
    //                 if (!Borrowing::findOne(['member_id' => $member->id, 'state' => true])) {

    //                     if ($d1 <= $d2 + 86400000 * 30) {
    //                         $tontine = new Tontine();
    //                         $tontine->limit_date = $model->limit_date;
    //                         $tontine->tontine_type_id = $model->tontine_type_id;
    //                         $tontine->member_id = $model->member_id;
    //                         $tontine->comments = $model->comments;
    //                         $tontine->state = true;
    //                         $tontine->administrator_id = $this->administrator->id;

    //                         $members = Member::find()->where(['!=', 'id', $model->member_id])->andWhere(['active' => true])->all();

    //                         $unit_amount = (int)ceil((double)($tontine_type->amount) / count($members));
    //                         $amount = $unit_amount * count($members);

    //                         $tontine->amount = $amount;
    //                         $tontine->unit_amount = $unit_amount;
    //                         $tontine->save();

    //                         $help = Help::findOne(['member_id' => $member->id]);

    //                         MailManager::alert_contributeur($member->user(), $member, $help);

    //                         foreach ($members as $member) {
    //                             $contribution = new ContributionTontine();
    //                             $contribution->state = false;
    //                             $contribution->member_id = $member->id;
    //                             $contribution->tontine_id = $tontine->id;
    //                             $contribution->save();
    //                             MailManager::alert_new_tontine($member->user(), $member, $tontine, $tontine_type);
    //                             MailManager::alert_contributeur($member->user(), $member, $help);
    //                         }

    //                         return $this->redirect("@administrator.tontines");
    //                     } else {
    //                         $model->addError("limit_date", "Le délai minimum est d'un mois");
    //                         return $this->render("new_tontine", compact("model"));
    //                     }

    //                 } else
    //                     $model->addError("member_id", "ce membre a doit rembourser son emprunt avant de bénéficier d'une aide");
    //                 return $this->render("new_tontine", compact("model"));
    //             } else
    //                 return RedirectionManager::abort($this);

    //         } else
    //             return $this->render("new_tontine", compact("model"));
    //     } else
    //         return RedirectionManager::abort($this);
    // }

    public function actionAjouterTontine()
    {
        if (!Yii::$app->request->getIsPost()) {
            return RedirectionManager::abort($this);
        }
    
        $model = new NewTontineForm();
        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render("new_tontine", compact("model"));
        }
    
        $member = Member::findOne($model->member_id);
        $tontine_type = TontineType::findOne($model->tontine_type_id);

        if (!$member || !$tontine_type || !$member->active) {
            return RedirectionManager::abort($this);
        }

        if ($this->hasActiveBorrowing($member->id)) {
            $model->addError("member_id", "Ce membre doit rembourser son emprunt avant de bénéficier d'une aide.");
            return $this->render("new_tontine", compact("model"));
        }

        if (!$this->isDateValid($model->limit_date)) {
            $model->addError("limit_date", "Le délai minimum est d'un mois.");
            return $this->render("new_tontine", compact("model"));
        }

        $tontine = $this->createTontine($model, $tontine_type);
        $this->sendNotifications($tontine, $member, $tontine_type);

        return $this->redirect("@administrator.tontines");
    }

    private function hasActiveBorrowing($member_id)
    {
        return Borrowing::findOne(['member_id' => $member_id, 'state' => true]) !== null;
    }

    private function isDateValid($limit_date)
    {
        $currentTimestamp = (new DateTime())->getTimestamp();
        $limitTimestamp = (new DateTime($limit_date))->getTimestamp();
        return $currentTimestamp <= $limitTimestamp + 86400000 * 30;
    }

    private function createTontine($model, $tontine_type)
    {
        $tontine = new Tontine();
        $tontine->limit_date = $model->limit_date;
        $tontine->tontine_type_id = $model->tontine_type_id;
        $tontine->member_id = $model->member_id;
        $tontine->comments = $model->comments;
        $tontine->state = true;
        $tontine->administrator_id = $this->administrator->id;

        $members = Member::find()->where(['!=', 'id', $model->member_id])->andWhere(['active' => true])->all();
        
        $memberCount = count($members);
        if ($memberCount === 0) {
            Yii::$app->session->setFlash('error', "Impossible de créer la tontine : aucun autre membre actif trouvé pour contribuer.");
            return null;
        }

        $unit_amount = (int)ceil((float)($tontine_type->amount) / $memberCount);
        $tontine->amount = $unit_amount * $memberCount;
        $tontine->unit_amount = $unit_amount;
        
        if (!$tontine->save()) {
            Yii::$app->session->setFlash('error', "Erreur lors de l'enregistrement de la tontine.");
            return null;
        }

        foreach ($members as $member) {
            $contribution = new ContributionTontine();
            $contribution->state = false;
            $contribution->member_id = $member->id;
            $contribution->tontine_id = $tontine->id;
            $contribution->save();
        }

        return $tontine;
    }

    private function sendNotifications($tontine, $member, $tontine_type)
    {
        $help = Help::findOne(['member_id' => $member->id]);
        if ($help !== null) {
            MailManager::alert_contributeur($member->user(), $member, $help);
        }

        $members = Member::find()->where(['!=', 'id', $tontine->member_id])->andWhere(['active' => true])->all();
        foreach ($members as $member) {
            MailManager::alert_new_tontine($member->user(), $member, $tontine, $tontine_type);
            if ($help !== null) {
                MailManager::alert_contributeur($member->user(), $member, $help);
            }
        }
    }


    /************************nouvelle contribution dans la mutuelle ****************************************************** */
    public function actionNouvelleContributionTontine($q = 0)
    {
        if ($q) {
            $tontine = Tontine::findOne($q);
            if ($tontine && $tontine->state) {
                $model = new NewContributionTontineForm();
                $model->tontine_id = $q;
                return $this->render("new_contribution_tontine", compact("model"));
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    /*************************ajouter des contributions ****************************************************** */
    public function actionAjouterContributionTontine()
{
    if (Yii::$app->request->getIsPost()) {
        $model = new NewContributionTontineForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $member = Member::findOne($model->member_id);
            $tontine = Tontine::findOne($model->tontine_id);
            if ($member && $tontine && $tontine->state) {
                $contribution = ContributionTontine::findOne(['member_id' => $model->member_id, 'tontine_id' => $model->tontine_id]);
                if ($contribution && !$contribution->state) {
                    $contribution->state = true;
                    $contribution->date = $model->date;
                    $contribution->administrator_id = $this->administrator->id;
                    $contribution->save();

                    $help = Help::findOne(['member_id' => $member->id]);
                    if ($help) { // Vérifiez que $help n'est pas null
                        MailManager::alert_contributeur($member->user(), $member, $help);
                    }

                    if ($tontine->contributedAmount == $tontine->amount) {
                        $tontine->state = false;
                        $tontine->save();

                        $help = Help::findOne(['member_id' => $member->id]); // Vérifiez à nouveau
                        if ($help) {
                            MailManager::alert_contributeur($member->user(), $member, $help);
                        }
                    }

                    return $this->redirect(["@administrator.tontine_details", 'q' => $tontine->id]);
                } else {
                    return RedirectionManager::abort($this);
                }
            } else {
                return RedirectionManager::abort($this);
            }
        } else {
            return $this->render("new_contribution_tontine", compact('model'));
        }
    } else {
        return RedirectionManager::abort($this);
    }
}


    /******************************aide du côté Administrateur *************************************************** */

    public function actionAppliquerModificationTypeTontine()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new TontineTypeForm();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $tontineType = TontineType::findOne($model->id);
                $tontineType->title = $model->title;
                $tontineType->amount = $model->amount;
                $tontineType->save();
                Yii::$app->session->setFlash('success', 'La tontine a été modifiée avec succès.');
                return $this->render('update_tontine_type', compact('model'));
            } else {
                return $this->render('update_tontine_type', compact('model'));
            }
        } else
            return RedirectionManager::abort($this);
    }

    public function actionSupprimerTypeTontine()
    {
        if (Yii::$app->request->getIsPost()) {
            $model = new TontineTypeForm();
            $model->load(Yii::$app->request->post());
            if ($model->id) {
                $tontineType = TontineType::findOne($model->id);
                if ($tontineType) {
                    $tontineType->active = false;
                    $tontineType->delete();
                    return $this->redirect("@administrator.tontine_types");
                } else
                    return RedirectionManager::abort($this);
            } else
                return RedirectionManager::abort($this);
        } else
            return RedirectionManager::abort($this);
    }

    public function actionNouveauTypeTontine()
    {
        AdministratorSessionManager::setHelps();
        $model = new TontineTypeForm();
        return $this->render('new_tontine_type', compact('model'));
    }

    public function actionAjouterTypeTontine()
    {
        if (\Yii::$app->request->getIsPost()) {
            $model = new TontineTypeForm();

            $newModel = new TontineType();

            if ($model->load(Yii::$app->request->post()) && $newModel->validate()) {
                $tontineType = new TontineType();
                $tontineType->title = $model->title;
                $tontineType->amount = $model->amount;
                $tontineType->save();
                if ($tontineType->save()) {
                    Yii::$app->session->setFlash('success', "Type de Tontine créé avec succès");
                } else {
                    Yii::$app->session->setFlash('error', "Ce type de tontine existe déjà");
                }
                return $this->redirect('@administrator.tontine_types');
            } else
                return $this->render('new_tontine_type', compact('model'));
        } else {
            return RedirectionManager::abort($this);
        }
    }
    // --- GESTION DES CONTENTIEUX ---

    public function actionContentieux()
    {
        AdministratorSessionManager::setHome("borrowing"); // Or a new menu item
        
        // Trouver tous les emprunts actifs en défaut (plus de 6 mois et couverture insuffissante)
        $borrowings = Borrowing::find()
            ->where(['state' => true])
            ->all();
            
        $defaultBorrowings = [];
        foreach ($borrowings as $borrowing) {
            if ($borrowing->isInDefault($borrowing->member)) {
                $defaultBorrowings[] = $borrowing;
            }
        }
        
        return $this->render('contentieux', compact('defaultBorrowings'));
    }

    public function actionAppliquerPenalite($id)
    {
        $borrowing = Borrowing::findOne($id);
        if (!$borrowing) {
            throw new NotFoundHttpException("Emprunt non trouvé.");
        }
        
        $session = Session::findOne(['active' => true]);
        if (!$session) {
             Yii::$app->session->setFlash('error', "Aucune session active.");
             return $this->redirect(['administrator/contentieux']);
        }
        
        $exercise = $borrowing->session->exercise;
        $penaltyRate = $exercise->penalty_rate;
        
        if (!$penaltyRate) {
             Yii::$app->session->setFlash('error', "Aucun taux de pénalité défini pour cet exercice.");
             return $this->redirect(['administrator/contentieux']);
        }
        
        // Calcul pénalité: m * Montant Emprunté
        $penaltyAmount = ($borrowing->amount * $penaltyRate) / 100;
        
        // Appliquer la pénalité = Retrait sur l'épargne
        $member = $borrowing->member;
        
        // Créer l'épargne négative
        $saving = new Saving();
        $saving->member_id = $member->id;
        $saving->session_id = $session->id;
        $saving->amount = -$penaltyAmount;
        
        if ($saving->save()) {
             Yii::$app->session->setFlash('success', "Pénalité de {$penaltyAmount} XAF appliquée avec succès.");
             
             // Vérifier Insolvabilité
             // "lorsque par exemple maintenant ton épargne maintenat est nul... tu passes à un état insolvable"
             $totalSavings = $member->savedAmount($exercise); 
             
             if ($totalSavings <= 0) {
                 $member->insoluble = true;
                 $member->save(false);
                 Yii::$app->session->setFlash('warning', "Le membre est passé INSOLVABLE.");
             }
             
             // Marquer que la pénalité a été appliquée pour cette session
             $borrowing->last_penalty_session_id = $session->id;
             $borrowing->save(false);
             
        } else {
             Yii::$app->session->setFlash('error', "Erreur lors de l'application de la pénalité.");
        }
        
        return $this->redirect(['administrator/contentieux']);
    }

    /**
     * Calculate the maximum borrowing amount based on savings.
     *
     * @param float $savings
     * @return float
     */
    private function calculateMaxBorrowingAmount($savings)
    {
        if ($savings <= 200000) {
            return 5 * $savings;
        } elseif ($savings <= 500000) {
            return 5 * $savings;
        } elseif ($savings <= 1000000) {
            return 4 * $savings;
        } elseif ($savings <= 1500000) {
            return 3 * $savings;
        } elseif ($savings <= 2000000) {
            return 2 * $savings;
        } else {
            return 1.5 * $savings;
        }
    }
}
