<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSecurityControlsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('security_controls')->delete();

        \DB::table('security_controls')->insert(array (

            array (
                'id' => 98,
                'name' => '5.01  Politiques de sécurité de l\'information',
                'description' => 'Assurer de manière continue la pertinence, l\'adéquation, l\'efficacité des orientations de la direction et de  son soutien à la sécurité de l\'information selon les exigences métier, légales, statutaires, réglementaires  et contractuelles.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 99,
                'name' => '5.02  Fonctions et responsabilités liées à la sécurité de l\'information',
                'description' => 'Établir une structure définie, approuvée et comprise pour la mise en œuvre, le fonctionnement et la  gestion de la sécurité de l\'information au sein de l\'organisation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 100,
                'name' => '5.03  Séparation des tâches',
                'description' => 'Réduire le risque de fraude, d\'erreur et de contournement des mesures de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 101,
                'name' => '5.04  Responsabilités de la direction',
                'description' => 'S’assurer que la direction comprend son rôle en matière de sécurité de l\'information et qu\'elle  entreprend des actions visant à garantir que tout le personnel est conscient de ses responsabilités liées  à la sécurité de l\'information et qu\'il les mène à bien.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 102,
                'name' => '5.05  Relations avec les autorités',
                'description' => 'Assurer la circulation adéquate de l\'information en matière de sécurité de l’information, entre  l\'organisation et les autorités légales, réglementaires et de surveillance pertinente.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 103,
                'name' => '5.06  Relations avec des groupes de travail spécialisés',
                'description' => 'Assurer la circulation adéquate de l\'information en matière de sécurité de l’information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 104,
                'name' => '5.07  Intelligence des menaces',
                'description' => 'Apporter une connaissance de l\'environnement des menaces de l\'organisation afin que les mesures  d\'atténuation appropriées puissent être prises.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 105,
                'name' => '5.08  Sécurité de l\'information dans la gestion de projet',
                'description' => 'Assurer que les risques de sécurité de l\'information relatifs aux projets et aux livrables sont traités  efficacement dans la gestion de projet, tout au long du cycle de vie du projet.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 106,
                'name' => '5.09  Inventaire des informations et des autres actifs associés',
                'description' => 'Identifier les informations et autres actifs associés de l\'organisation afin de préserver leur sécurité et  d\'en attribuer la propriété de manière appropriée.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 107,
                'name' => '5.10  Utilisation correcte de l\'information et des autres actifs associés',
                'description' => 'Assurer que les informations et autres actifs associés sont protégés, utilisés et traités de manière  appropriée.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 108,
                'name' => '5.11  Restitution des actifs',
                'description' => 'Protéger les actifs de l\'organisation dans le cadre du processus du changement ou de la fin de leur  emploi, contrat ou accord.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 109,
                'name' => '5.12  Classification de l\'information',
                'description' => 'Assurer l\'identification et la compréhension des besoins de protection de l\'information en fonction de son importance pour l\'organisation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 110,
                'name' => '5.13  Marquage des informations',
                'description' => 'Faciliter la communication de la classification de l\'information et appuyer l\'automatisation de la gestion  et du traitement de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 111,
                'name' => '5.14  Transfert de l\'information',
                'description' => 'Maintenir la sécurité de l\'information transférée au sein de l\'organisation et vers toute partie intéressée  externe',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 112,
                'name' => '5.15  Contrôle d\'accès',
                'description' => 'Assurer l\'accès autorisé et empêcher l\'accès non autorisé aux informations et autres actifs associés.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 113,
                'name' => '5.16  Gestion des identités',
                'description' => 'Permettre l\'identification unique des personnes et des systèmes qui accèdent aux informations et  autres actifs associés de l\'organisation, et pour permettre l’attribution appropriée des droits d\'accès.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 114,
                'name' => '5.17  Informations d\'authentification',
                'description' => 'Assurer l\'authentification correcte de l\'entité et éviter les défaillances des processus d\'authentification.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 115,
                'name' => '5.18  Droits d\'accès',
                'description' => 'Assurer que l\'accès aux informations et aux autres actifs associés est défini et autorisé conformément aux  exigences métier',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 116,
                'name' => '5.19  Sécurité de l\'information dans les relations avec les fournisseurs',
                'description' => 'Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 117,
                'name' => '5.20  Prise en compte de la sécurité de l\'information dans les accords conclus avec les fournisseurs',
                'description' => 'Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 118,
                'name' => '5.21  Management de la sécurité de l\'information dans la chaîne d\'approvisionnement TIC',
                'description' => 'Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 119,
                'name' => '5.22  Suivi, revue et gestion des changements des services fournisseurs',
                'description' => 'Maintenir un niveau convenu de sécurité de l\'information et de prestation de services, conformément  aux accords conclus avec les fournisseurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 120,
                'name' => '5.23  Sécurité de l\'information dans l\'utilisation de services en nuage',
                'description' => 'Spécifier et gérer la sécurité de l\'information lors de l\'utilisation de services en nuage.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 121,
                'name' => '5.24  Planification et préparation de la gestion des incidents liés à la sécurité de l\'information',
                'description' => 'Assurer une réponse rapide, efficace, cohérente et ordonnée aux incidents de sécurité de l\'information,  notamment la communication sur les événements de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 122,
                'name' => '5.25  Appréciation des événements liés à la sécurité de l\'information et prise de décision',
                'description' => 'Assurer une catégorisation et une priorisation efficaces des événements de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 123,
                'name' => '5.26  Réponse aux incidents liés à la sécurité de l\'information',
                'description' => 'Assurer une réponse efficace et effective aux incidents de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 124,
                'name' => '5.27  Tirer des enseignements des incidents liés à la sécurité de l\'information',
                'description' => 'Réduire la probabilité ou les conséquences des incidents futurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 125,
                'name' => '5.28  Recueil de preuves',
                'description' => 'Assurer une gestion cohérente et efficace des preuves relatives aux incidents de sécurité de l\'information  pour les besoins d\'actions judiciaires ou de disciplinaires.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 126,
                'name' => '5.29  Sécurité de l\'information durant une perturbation',
                'description' => 'Protéger les informations et autres actifs associés pendant une perturbation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 127,
                'name' => '5.30  Préparation des TIC pour la continuité d\'activité',
                'description' => 'Assurer la disponibilité des informations et autres actifs associés de l\'organisation pendant une  perturbation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 128,
                'name' => '5.31  Identification des exigences légales, statutaires, réglementaires et contractuelles',
                'description' => 'Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives à la  sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 129,
                'name' => '5.32  Droits de propriété intellectuelle',
                'description' => 'Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives aux  droits de propriété intellectuelle et à l\'utilisation de produits propriétaires.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 130,
                'name' => '5.33  Protection des enregistrements',
                'description' => 'Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles, ainsi  qu\'aux attentes de la société ou de la communauté relatives à la protection et à la disponibilité des  enregistrements.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 131,
                'name' => '5.34  Vie privée et protection des DCP',
                'description' => 'Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives aux  aspects de la sécurité de l\'information portant sur la protection des DCP.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 132,
                'name' => '5.35  Revue indépendante de la sécurité de l\'information',
                'description' => 'S’assurer que l’approche de l’organisation pour gérer la sécurité de l’information est continuellement  adaptée, adéquate et efficace.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 133,
                'name' => '5.36  Conformité aux politiques et normes de sécurité de l\'information',
                'description' => 'S’assurer que la sécurité de l\'information est mise en œuvre et fonctionne conformément à la politique  de sécurité de l\'information, aux politiques spécifiques à une thématique, aux règles et aux normes de  l\'organisation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 134,
                'name' => '5.37  Procédures d\'exploitation documentées',
                'description' => 'S\'assurer du fonctionnement correct et sécurisé des moyens de traitement de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 135,
                'name' => '6.01  Présélection',
                'description' => 'S\'assurer que tous les membres du personnel sont éligibles et adéquats pour remplir les fonctions pour lesquelles ils sont candidats, et qu\'ils le restent tout au long de leur emploi.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 136,
                'name' => '6.02  Conditions générales d\'embauche',
                'description' => 'S\'assurer que le personnel comprend ses responsabilités en termes de sécurité de l\'information dans le cadre des fonctions que l’organisation envisage de lui confier.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 137,
                'name' => '6.03  Sensibilisation, apprentissage et formation à la sécurité de l\'information',
                'description' => 'S\'assurer que le personnel et les parties intéressées pertinentes connaissent et remplissent leurs responsabilités en matière de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 138,
                'name' => '6.04  Processus disciplinaire',
                'description' => 'S\'assurer que le personnel et d’autres parties intéressées pertinentes comprennent les conséquences  des violations de la politique de sécurité de l\'information, prévenir ces violations, et traiter de manière  appropriée le personnel et d’autres parties intéressées qui ont commis des violations.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 139,
                'name' => '6.05  Responsabilités consécutivement à la fin ou à la modification du contrat de tr',
                'description' => 'Protéger les intérêts de l\'organisation dans le cadre du processus de changement ou de fin d\'un emploi  ou d’un contrat.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 140,
                'name' => '6.06  Engagements de confidentialité ou de non-divulgation',
                'description' => 'Assurer la confidentialité des informations accessibles par le personnel ou des parties externes.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 141,
                'name' => '6.07  Travail à distance',
                'description' => 'Assurer la sécurité des informations lorsque le personnel travaille à distance.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 142,
                'name' => '6.08  Signalement des événements liés à la sécurité de l\'information',
                'description' => 'Permettre la déclaration des événements de sécurité de l\'information qui peuvent être identifiés par le  personnel, de manière rapide, cohérente et efficace.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 143,
                'name' => '7.01  Périmètre de sécurité physique',
                'description' => 'Empêcher l’accès physique non autorisé, les dommages ou interférences portant sur les informations et  autres actifs associés de l\'organisation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 144,
                'name' => '7.02  Contrôles physiques des accès',
                'description' => 'Assurer que seul l\'accès physique autorisé aux informations et autres actifs associés de l\'organisation  soit possible.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 145,
                'name' => '7.03  Sécurisation des bureaux, des salles et des équipements',
                'description' => 'Empêcher l’accès physique non autorisé, les dommages et les interférences impactant les informations et autres actifs associés de l\'organisation dans les bureaux, salles et installations.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 146,
                'name' => '7.04  Surveillance de la sécurité physique',
                'description' => 'Détecter et dissuader l’accès physique non autorisé.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 147,
                'name' => '7.05  Protection contre les menaces physiques et environnementales',
                'description' => 'Prévenir ou réduire les conséquences des événements issus des menaces physiques ou environnementales.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 148,
                'name' => '7.06  Travail dans les zones sécurisées',
                'description' => 'Protéger les informations et autres actifs associés dans les zones sécurisées contre tout dommage et  contre toutes interférences non autorisées par le personnel travaillant dans ces zones.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 149,
                'name' => '7.07  Bureau propre et écran vide',
                'description' => 'Réduire les risques d\'accès non autorisé, de perte et d\'endommagement des informations sur les  bureaux, les écrans et dans d’autres emplacements accessibles pendant et en dehors des heures  normales de travail.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 150,
                'name' => '7.08  Emplacement et protection du matériel',
                'description' => 'Réduire les risques liés à des menaces physiques et environnementales, et à des accès non autorisés et  à des dommages.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 151,
                'name' => '7.09  Sécurité des actifs hors des locaux',
                'description' => 'Empêcher la perte, l\'endommagement, le vol ou la compromission des terminaux hors du site et  l\'interruption des activités de l\'organisation.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 152,
                'name' => '7.10  Supports de stockage',
                'description' => 'Assurer que seuls la divulgation, la modification, le retrait ou la destruction autorisés des informations  de l\'organisation sur des supports de stockage sont effectués.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 153,
                'name' => '7.11  Services généraux',
                'description' => 'Empêcher la perte, l\'endommagement ou la compromission des informations et autres actifs associés,  ou l\'interruption des activités de l\'organisation, causés par les défaillances et les perturbations des  services supports.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 154,
                'name' => '7.12  Sécurité du câblage',
                'description' => 'Empêcher la perte, l\'endommagement, le vol ou la compromission des informations et autres actifs  associés et l\'interruption des activités de l\'organisation liés au câblage électrique et de communications.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 155,
                'name' => '7.13  Maintenance du matériel',
                'description' => 'Empêcher la perte, l\'endommagement, le vol ou la compromission des informations et autres actifs  associés et l\'interruption des activités de l\'organisation causés par un manque de maintenance.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 156,
            'name' => '7.14  Mise au rebut ou recyclage sécurisé(e) du matériel',
                'description' => 'Éviter la fuite d\'informations à partir de matériel à éliminer ou à réutiliser.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 157,
                'name' => '8.01  Terminaux utilisateurs',
                'description' => 'Protéger les informations contre les risques liés à l\'utilisation de terminaux finaux des utilisateurs.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 158,
                'name' => '8.02  Privilèges d\'accès',
                'description' => 'S\'assurer que seuls les utilisateurs, composants logiciels et services autorisés sont dotés de droits d\'accès privilégiés.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 159,
                'name' => '8.03  Restriction d\'accès à l\'information',
                'description' => 'Assurer les accès autorisés seulement et empêcher les accès non autorisés aux informations et autres actifs associés.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 160,
                'name' => '8.04  Accès au code source',
                'description' => 'Empêcher l\'introduction d\'une fonctionnalité non autorisée, éviter les modifications non intentionnelles  ou malveillantes et préserver la confidentialité de la propriété intellectuelle importante.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 161,
                'name' => '8.05  Authentification sécurisée',
                'description' => 'S\'assurer qu\'un utilisateur ou une entité est authentifié de façon sécurisée lorsque l\'accès aux systèmes, applications et services lui est accordé.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 162,
                'name' => '8.06  Dimensionnement',
                'description' => 'Assurer les besoins en termes de moyens de traitement de l\'information, de ressources humaines, de bureaux et autres installations.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 163,
                'name' => '8.07  Protection contre les programmes malveillants',
                'description' => 'S’assurer que les informations et autres actifs associés sont protégés contre les programmes malveillants.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 164,
                'name' => '8.08  Gestion des vulnérabilités techniques',
                'description' => 'Empêcher l’exploitation des vulnérabilités techniques.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 165,
                'name' => '8.09  Gestion de la configuration',
                'description' => 'S\'assurer que le matériel, les logiciels, les services et les réseaux fonctionnent correctement avec les paramètres de sécurité requis, et que la configuration n’est pas altérée par des changements non autorisés ou incorrects.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 166,
                'name' => '8.10  Suppression d\'information',
                'description' => 'Empêcher l\'exposition inutile des informations sensibles et se conformer aux exigences légales, statutaires, réglementaires et contractuelles relatives à la suppression d\'informations.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 167,
                'name' => '8.11  Masquage des données',
                'description' => 'Limiter l\'exposition des données sensibles, y compris les DCP, et se conformer aux exigences légales, statutaires, réglementaires et contractuelles.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 168,
                'name' => '8.12  Prévention de la fuite de données',
                'description' => 'Détecter et empêcher la divulgation et l\'extraction non autorisées d\'informations par des personnes ou des systèmes.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 169,
                'name' => '8.13  Sauvegarde des informations',
                'description' => 'Permettre la récupération en cas de perte de données ou de systèmes.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 170,
                'name' => '8.14  Redondance des moyens de traitement de l\'information',
                'description' => 'S\'assurer du fonctionnement continu des moyens de traitement de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 171,
                'name' => '8.15  Journalisation',
                'description' => 'Enregistrer les événements, générer des preuves, assurer l\'intégrité des informations de journalisation, empêcher les accès non autorisés, identifier les événements de sécurité de l\'information qui peuvent engendrer un incident de sécurité de l\'information et assister les investigations.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 172,
                'name' => '8.16  Activités de surveillance',
                'description' => 'Détecter les comportements anormaux et les éventuels incidents de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 173,
                'name' => '8.17  Synchronisation des horloges',
                'description' => 'Permettre la corrélation et l\'analyse d’événements de sécurité et autres données enregistrées, assister les investigations sur les incidents de sécurité de l\'information.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 174,
                'name' => '8.18  Utilisation de programmes utilitaires à privilèges',
                'description' => 'S\'assurer que l\'utilisation de programmes utilitaires ne nuise pas aux mesures de sécurité de  l\'information des systèmes et des applications.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 175,
                'name' => '8.19  Installation de logiciels sur des systèmes en exploitation',
                'description' => 'Assurer l\'intégrité des systèmes opérationnels et empêcher l\'exploitation des vulnérabilités techniques.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 176,
                'name' => '8.20  Mesures liées aux réseaux',
                'description' => 'Protéger les informations dans les réseaux et les moyens de traitement de l\'information support contre  les compromission via le réseau.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 177,
                'name' => '8.21  Sécurité des services en réseau',
                'description' => 'Assurer la sécurité lors de l\'utilisation des services réseau.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 178,
                'name' => '8.22  Filtrage Internet',
                'description' => 'Diviser le réseau en périmètres de sécurité et contrôler le trafic entre eux en fonction des besoins  métier.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 179,
                'name' => '8.23  Cloisonnement des réseaux',
                'description' => 'Protéger les systèmes contre la compromission des programmes malveillants et empêcher l\'accès aux  ressources web non autorisées.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 180,
                'name' => '8.24  Utilisation de la cryptographie',
                'description' => 'Assurer l’utilisation correcte et efficace de la cryptographie afin de protéger la confidentialité,  l\'authenticité ou l\'intégrité des informations conformément aux exigences métier et de sécurité de  l\'information, et en tenant compte des exigences légales, statutaires, réglementaires et contractuelles  relatives à la cryptographie.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 181,
                'name' => '8.25  Cycle de vie de développement sécurisé',
                'description' => 'S\'assurer que la sécurité de l\'information est conçue et mise en œuvre au cours du cycle de vie de  développement sécurisé des logiciels et des systèmes.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 182,
                'name' => '8.26  Exigences de sécurité des applications',
                'description' => 'S\'assurer que toutes les exigences de sécurité de l\'information sont identifiées et traitées lors du  développement ou de l’acquisition d\'applications.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 183,
                'name' => '8.27  Principes d\'ingénierie et d\'architecture système sécurisée',
                'description' => 'S\'assurer que les systèmes d\'information sont conçus, mis en œuvre et exploités de manière sécurisée  au cours du cycle de vie de développement.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 184,
                'name' => '8.28  Codage sécurisé',
                'description' => 'S\'assurer que les logiciels sont développés de manière sécurisée afin de réduire le nombre d’éventuelles  vulnérabilités de sécurité de l\'information dans les logiciels.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 185,
                'name' => '8.29  Tests de sécurité dans le développement et l\'acceptation',
                'description' => 'Valider le respect des exigences de sécurité de l\'information lorsque des applications ou des codes sont  déployés dans l\'environnement .',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 186,
                'name' => '8.30  Développement externalisé',
                'description' => 'S\'assurer que les mesures de sécurité de l\'information requises par l\'organisation sont mises en œuvre  dans le cadre du développement externalisé des systèmes.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 187,
                'name' => '8.31  Séparation des environnements de développement, de test et de production',
                'description' => 'Protéger l\'environnement opérationnel et les données correspondantes contre les compromissions qui  pourraient être dues aux activités de développement et de test.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 188,
                'name' => '8.32  Gestion des changements',
                'description' => 'Préserver la sécurité de l\'information lors de l\'exécution des changements.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 189,
                'name' => '8.33  Informations relatives aux tests',
                'description' => 'Assurer la pertinence des tests et la protection des informations opérationnelles utilisées pour les tests.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),

            array (
                'id' => 190,
                'name' => '8.34  Protection des systèmes d\'information en cours d\'audit et de test',
                'description' => 'Minimiser l\'impact des activités d\'audit et autres activités d\'assurance sur les systèmes opérationnels  et les processus métier.',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));


    }
}
