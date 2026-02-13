<?php

namespace app\helpers;

use app\models\Member;

class MemberStatusHelper
{
    const STATUS_INSCRIT = 'inscrit';
    const STATUS_ACTIF = 'actif';
    const STATUS_INACTIF = 'inactif';

    public static function getStatus(Member $member)
    {
        $status = self::STATUS_INACTIF;

        // Vérifier si l'inscription est payée
        if ($member->inscription === 1) {
            $status = self::STATUS_INSCRIT;
        }

        // Si le fond social est aussi payé, le membre est actif
        if ($member->social_crown === 1) {
            $status = self::STATUS_ACTIF;
        }

        return $status;
    }

    public static function getStatusLabel(Member $member)
    {
        $status = self::getStatus($member);
        
        switch ($status) {
            case self::STATUS_ACTIF:
                return '<span class="badge badge-success">Actif</span>';
            case self::STATUS_INSCRIT:
                return '<span class="badge badge-warning">Inscrit</span>';
            default:
                return '<span class="badge badge-danger">Inactif</span>';
        }
    }

    public static function getStatusTooltip(Member $member)
    {
        $status = self::getStatus($member);
        
        switch ($status) {
            case self::STATUS_ACTIF:
                return 'Inscription et fond social payés';
            case self::STATUS_INSCRIT:
                return 'Inscription payée, fond social non payé';
            default:
                return 'Aucun paiement effectué';
        }
    }
}
