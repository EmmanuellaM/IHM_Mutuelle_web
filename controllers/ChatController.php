<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ChatMessage;
use app\models\Member;
use app\models\User;
use app\models\Administrator;
use yii\filters\AccessControl;
use app\managers\AdministratorSessionManager;
use app\managers\MemberSessionManager;

class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            $user = User::findOne(Yii::$app->user->id);
            $this->view->params['user'] = $user;
            
            if (Yii::$app->user->identity->type === 'ADMINISTRATOR') {
                $this->layout = 'administrator_base';
                $administrator = Administrator::findOne(['user_id' => $user->id]);
                $this->view->params['administrator'] = $administrator;
                AdministratorSessionManager::setHome('chat');
            } else {
                $this->layout = 'member_base';
                $member = Member::findOne(['user_id' => $user->id]);
                if ($member === null) {
                    throw new \yii\web\NotFoundHttpException('Membre non trouvé.');
                }
                $this->view->params['member'] = $member;
                MemberSessionManager::setChat();
            }
        }
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $currentUser = User::findOne(Yii::$app->user->id);
        
        // Récupérer tous les utilisateurs sauf l'utilisateur actuel
        $users = User::find()
            ->where(['!=', 'id', Yii::$app->user->id])
            ->all();

        return $this->render('index', [
            'currentUser' => $currentUser,
            'users' => $users,
            'currentUserId' => Yii::$app->user->id
        ]);
    }

    public function actionSendMessage()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Non autorisé'];
        }

        try {
            $request = Yii::$app->request->getRawBody();
            $data = json_decode($request, true);
            
            if (!isset($data['message']) || !isset($data['receiver_id'])) {
                return ['success' => false, 'message' => 'Données invalides'];
            }

            $chatMessage = new ChatMessage();
            $chatMessage->message = $data['message'];
            $chatMessage->sender_id = Yii::$app->user->id;
            $chatMessage->receiver_id = $data['receiver_id'];
            $chatMessage->created_at = time();

            if ($chatMessage->save()) {
                $sender = User::findOne($chatMessage->sender_id);
                $senderName = $sender->type === 'ADMINISTRATOR' ? 
                    Administrator::findOne(['user_id' => $sender->id])->username : 
                    Member::findOne(['user_id' => $sender->id])->username;
                
                return [
                    'success' => true,
                    'message' => [
                        'id' => $chatMessage->id,
                        'sender_id' => $chatMessage->sender_id,
                        'receiver_id' => $chatMessage->receiver_id,
                        'sender_name' => $senderName,
                        'message' => htmlspecialchars($chatMessage->message),
                        'created_at' => $chatMessage->created_at
                    ]
                ];
            }

            return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement du message', 'errors' => $chatMessage->errors];
        } catch (\Exception $e) {
            Yii::error('Erreur lors de l\'envoi du message: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur est survenue lors de l\'envoi du message'];
        }
    }

    public function actionGetMessages()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Non autorisé'];
        }

        try {
            $receiverId = Yii::$app->request->get('receiver_id');
            if (!$receiverId) {
                return ['success' => false, 'message' => 'ID du destinataire manquant'];
            }

            $messages = ChatMessage::find()
                ->where([
                    'or',
                    [
                        'sender_id' => Yii::$app->user->id,
                        'receiver_id' => $receiverId
                    ],
                    [
                        'sender_id' => $receiverId,
                        'receiver_id' => Yii::$app->user->id
                    ]
                ])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            $formattedMessages = [];
            foreach ($messages as $message) {
                $sender = User::findOne($message->sender_id);
                if (!$sender) continue;
                
                $senderName = $sender->type === 'ADMINISTRATOR' ? 
                    Administrator::findOne(['user_id' => $sender->id])->username : 
                    Member::findOne(['user_id' => $sender->id])->username;

                $formattedMessages[] = [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $senderName,
                    'created_at' => $message->created_at
                ];
            }

            return [
                'success' => true,
                'messages' => $formattedMessages
            ];
        } catch (\Exception $e) {
            Yii::error('Erreur lors de la récupération des messages: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur est survenue lors de la récupération des messages'];
        }
    }
}
