<?php

namespace Infrastructure\Symfony\Service;

class SeoService
{
    private array $defaultMeta = [
        'title' => 'SteamTec - Solutions éco-responsables de nettoyage et désherbage',
        'description' => 'ENTECH, fabricant français de solutions éco-responsables efficaces de nettoyage et de désherbage, spécialistes de la vapeur depuis plus de 20 ans.',
        'keywords' => 'nettoyage vapeur, nettoyage vapeur professionnel, nettoyage écologique, désherbage écologique, désherbage sans produit chimique, ENTECH, STEAMTEC, STEAM_TEC, fabricant français, solution alternative de nettoyage',
        'author' => 'SteamTec',
        'robots' => 'index, follow',
        'og:type' => 'website',
        'og:site_name' => 'SteamTec',
        'twitter:card' => 'summary_large_image',
        'twitter:site' => '@steamtec',
    ];

    private array $pageMeta = [
        'home' => [
            'title' => 'SteamTec - Nettoyeur et désherbeur vapeur professionnel',
            'description' => 'Découvrez nos solutions éco-responsables de nettoyage et désherbage à la vapeur. Fabricant français depuis plus de 20 ans. Leader du marché français du nettoyage basse pression vapeur.',
            'keywords' => 'nettoyage vapeur, nettoyage vapeur professionnel, nettoyage écologique, meilleur nettoyeur vapeur toiture, nettoyage sans produit chimique, désherbage écologique, désherbage sans produit chimique, ENTECH, STEAMTEC, STEAM_TEC, solution alternative de nettoyage, fabriqué en France',
        ],
        'about' => [
            'title' => 'À propos de SteamTec - Fabricant français de solutions éco-responsables',
            'description' => 'ENTECH, fabricant français spécialisé dans les solutions de nettoyage et désherbage à la vapeur depuis plus de 20 ans.',
            'keywords' => 'ENTECH, ENTEC, STEAMTEC, STEAM_TEC, STEAM TEC, fabricant français, fabriqué en France, nettoyage vapeur, désherbage écologique',
        ],
        'societe' => [
            'title' => 'Fabricant français de matériel de nettoyage et désherbage à vapeur et eau chaude - ENTECH',
            'description' => 'Découvrez l\'histoire et les valeurs de ENTECH, fabricant français de la STEAM_TEC, solution écologique de nettoyage et désherbage depuis 2003.',
            'keywords' => 'SteamTec, STEAM_TEC, STEAM TEC, STEAMTECH, STEAM_TECH, STEAM TECH, ENTECH, ENTEC, histoire, valeurs, fabricant français, fabricant français nettoyeur vapeur, fabriqué en France',
        ],
        'configuration' => [
            'title' => 'SteamTec - Solutions personnalisées de nettoyage vapeur basse pression, eau chaude et eau surchauffée - ENTECH',
            'description' => 'Configurez votre solution SteamTec selon vos besoins spécifiques. Solutions évolutives et personnalisées. Besoin de mobilité et de disponibilité du véhicule, Besoin de maniabilité seul, Besoin d\'autonomie, Un besoinspécifique, ENTECH s\'adapte !',
            'keywords' => 'configuration, personnalisation, solutions évolutives, SteamTec, STEAM_TEC, machine de nettoyage sur remorque, nettoyeur vapeur sur remorque, machine de nettoyage sur roulettes, solution alternative de nettoyage',
        ],
        'contact' => [
            'title' => 'Contacter SteamTec par ENTECH- Nous contacter',
            'description' => 'Contactez ENTECH, pour vos besoins en solutions professionnelles de nettoyage extérieur - STEAM_TEC.',
            'keywords' => 'contact, SteamTec, STEAM_TEC, devis, information, prix, tarif, location, nettoyeur vapeur professionnel, désherbeur écologique',
        ],
        'client' => [
            'title' => 'SteamTec Solutions de Nettoyage et de désherbage pour les professionnels - ENTECH',
            'description' => 'A qui s\'adresse la STEAM_Tec ? Sociétés de nettoyage, sociétés dans le bâtiment (couvreurs, peintres, aérogommeurs, ...), tailleurs de pierre et conservateur du patrimoine, créateurs d\'entreprise, collectivités, paysagistes, ateliers d\'insertion, propriétaire fonciers, Camping, parcs de loisirs et beaucoup d\'autres ...',
            'keywords' => 'clients SteamTec, témoignages, professionnels, nettoyeur vapeur professionnel, nettoyeur eau chaude professionnel, nettoyeur eau surchauffée nettoyage vapeur professionnel, désherbage professionnel, collectivités',
        ],
        'nettoyage' => [
            'title' => 'Nettoyage vapeur basse pression professionnel - SteamTec',
            'description' => 'Solutions de nettoyage vapeur professionnel pour tous types de surfaces. Technologie française éco-responsable. La STEAM_Tec est un excellent nettoyeur basse pression vapeur professionnel. Il assure un résultat parfait sans aucune difficulté. Le nettoyage est basé sur l\'action de la vapeur et non sur la pression, vous pouvez utiliser la STEAM_Tec sur tous types de supports sans les altérer.',
            'keywords' => 'nettoyage vapeur, nettoyage vapeur professionnel, nettoyage vapeur toiture, nettoyage vapeur basse pression, nettoyage vapeur façade, nettoyage vapeur terrasse, nettoyage vapeur bois, nettoyage vapeur monuments, nettoyage vapeur bâtiment classé, nettoyage vapeur bâtiment commercial, nettoyage vapeur bâtiment industriel, nettoyage écologique, nettoyage sans produit chimique, nettoyage eau chaude, nettoyeur vapeur, nettoyeur écologique, solution alternative de nettoyage',
        ],
        'desherbage' => [
            'title' => 'Désherbage vapeur ou eau chaude - SteamTec Solution écologique sans produit chimique',
            'description' => 'Le désherbage eau chaude à 150 °C provoque la dilatation de l\'eau contenue dans les cellules des plantes jusqu\'à en provoquer leurs éclatements. De plus, au-delà de 80 °C les protéines chlorophylliennes contenues dans les plantes coagulent et tout le processus de photosynthèse est stoppé.',
            'keywords' => 'désherbage sans produit chimique, désherbage sans produit phytosanitaire, désherbage écologique, désherbage alternatif, désherbage vapeur, désherbage eau chaude, désherbage naturel, désherbage professionnel, désherbage collectivité, désherbage thermique, désherbeur écologique, désherbeur vapeur, désherbeur professionnel, désherbeuse écologique, solution alternative de nettoyage',
        ],
        'materiel' => [
            'title' => 'Matériel professionnel de nettoyage et désherbage Vapeur basse pression - SteamTec',
            'description' => 'De construction fiable et robuste, la STEAM_Tec est le matériel le plus performant du marché concernant le respect du support, la consommation en eau et en énergie, grâce à son principe de vapeur basse pression. Les forces de la société ENTECH sont sa réactivité, son savoir-faire perfectionné par 2 décennies de développement et l\'accompagnement de ses clients dans leur activité.',
            'keywords' => 'matériel nettoyage, matériel désherbage, vapeur, fabrication française, fabriqué en France, machine sur remorque, meilleur matériel de nettoyage vapeur, nettoyeur vapeur professionnel, désherbeur professionnel, STEAMTEC, ENTECH',
        ],
        'accessoire' => [
            'title' => 'Accessoires SteamTec - Des équipements de nettoyage et de désherbage adaptés à chacun - ENTECH',
            'description' => 'ENTECH propose une gamme adaptée et très variée d\'accessoires pour le nettoyage et le désherbage vapeur d\'eau de façon à répondre à tous vos besoins. Tous nos accessoires ont spécialement été choisis pour résister aux contraintes de température et de pression. Nos machines sont assemblées depuis près de 20 ans dans notre atelier dans les Vosges. Nous restons à votre disposition pour toute information complémentaire...',
            'keywords' => 'accessoires SteamTec, pièces détachées, maintenance, équipements, STEAMTEC, STEAM_TEC, nettoyeur vapeur, désherbeur écologique',
        ],
        'legal' => [
            'title' => 'Mentions légales - SteamTec - ENTECH',
            'description' => 'Mentions légales de SteamTec - ENTECH. Informations légales, propriété intellectuelle, données personnelles et conditions d\'utilisation.',
            'keywords' => 'mentions légales, SteamTec, ENTECH, conditions d\'utilisation, propriété intellectuelle, données personnelles, RGPD, cookies',
        ], 
    ];

    public function getMetaForPage(string $page, array $customMeta = []): array
    {
        $meta = $this->defaultMeta;
        
        if (isset($this->pageMeta[$page])) {
            $meta = array_merge($meta, $this->pageMeta[$page]);
        }
        
        return array_merge($meta, $customMeta);
    }

    public function generateStructuredData(string $type, array $data): array
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => $type,
        ];

        return array_merge($structuredData, $data);
    }

    public function getOrganizationStructuredData(): array
    {
        return $this->generateStructuredData('Organization', [
            'name' => 'SteamTec',
            'url' => 'https://steamtec.fr',
            'logo' => 'https://steamtec.fr/assets/images/logo.png',
            'description' => 'Fabricant français de matériel de nettoyage et désherbage à vapeur et eau chaude - ENTECH',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'FR',
                'addressLocality' => 'Bulgnéville',
                'postalCode' => '88140',
                'streetAddress' => 'ZA DU MOULIN'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+33 (0)3 29 09 15 78',
                'contactType' => 'customer service',
                'email' => 'contact@steamtec.fr'
            ]
        ]);
    }
}
