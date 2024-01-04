<?php

namespace App\Services;
class OpenApiFormater
{

    static function cleanLinkedinHtml($html)
    {
        $removable = [
            '_ ',
            'Voir le profil complet de',
            'relation de 1er niveau',
            'relation de 2e niveau',
            'relation de 3e niveau',
            '1er',
            '2e',
            '3e',
            '…voir plus',
            'relation de',
            'Coordonnées',
            'Activité',
            'Expérience',
            'Formation',
            'Licences et certifications',
            'Projets',
            'Bénévolat',
            'Compétences',
            'Langues',
            'Centres d’intérêt',
            'À propos',
            '<!--',
            '-->',
            'Se connecter',
            'Message',
            'Plus',
            'Afficher les détails',
            'Tout voir',
            'Précédent',
            'Suivant',
            'Posts chargés',
            'Compétences recommandées par',
            'recommandation de compétence',
            'Afficher les',
            'Compétence professionnelle',
            'Bilingue ou langue natale',
            'Relation de',
            'Voir le profil complet de',
        ];

        foreach ($removable as $item) {
            $html = preg_replace('/' . $item . '/', '', $html);
        }


        $html = preg_replace('/\s+/', ' ', $html);

        return trim($html);
    }

    static function addInstructionForLinkedin($html)
    {
        $information = [
            'lastname',
            'firstname',
            'email',
            'poste',
            'entreprise',
            'lieu',
            'description',
            'éducation',
            'competences',
            'langues',
            'interets',
            'url du profil',
        ];

        $beforeHtml = "Voici le contenu d'un profil linkedin, je veux que tu me renvoies les informations essentielles suivantes : ";
        foreach ($information as $item) {
            $beforeHtml .= $item . ', ';
        }
        $beforeHtml = substr($beforeHtml, 0, -2);
        $beforeHtml .= '. Ne me renvoie pas les informations inutiles et renvoie moi tout en json.';
        $beforeHtml .= 'si tu trouve pas une information, renvoi moi la clé avec le contenu vide.';
        $beforeHtml .= 'utilise bien le nom de la clé, le contenu est en francais.';

        $beforeHtml .= 'Voici le contenu du profil : ';

        $html = $beforeHtml . $html;

        return $html;
    }
}
