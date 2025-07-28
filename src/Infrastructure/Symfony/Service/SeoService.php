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
            'title' => 'SteamTec - Solutions éco-responsables pour un avenir propre',
            'description' => 'Découvrez nos solutions éco-responsables de nettoyage et désherbage à la vapeur. Fabricant français depuis plus de 20 ans.',
            'keywords' => 'nettoyage vapeur, nettoyage vapeur professionnel, nettoyage écologique, nettoyage sans produit chimique, désherbage écologique, désherbage sans produit chimique, ENTECH, STEAMTEC, STEAM_TEC, solution alternative de nettoyage, fabriqué en France',
        ],
        'about' => [
            'title' => 'À propos de SteamTec - Fabricant français de solutions éco-responsables',
            'description' => 'ENTECH, fabricant français spécialisé dans les solutions de nettoyage et désherbage à la vapeur depuis plus de 20 ans.',
            'keywords' => 'ENTECH, ENTEC, STEAMTEC, STEAM_TEC, STEAM TEC, fabricant français, fabriqué en France, nettoyage vapeur, désherbage écologique',
        ],
        'societe' => [
            'title' => 'SteamTec - Notre société et nos valeurs',
            'description' => 'Découvrez l\'histoire et les valeurs de SteamTec, fabricant français de solutions éco-responsables.',
            'keywords' => 'SteamTec, STEAM_TEC, STEAM TEC, STEAMTECH, STEAM_TECH, STEAM TECH, ENTECH, ENTEC, histoire, valeurs, fabricant français, fabriqué en France',
        ],
        'configuration' => [
            'title' => 'Configuration SteamTec - Solutions personnalisées',
            'description' => 'Configurez votre solution SteamTec selon vos besoins spécifiques. Solutions évolutives et personnalisées.',
            'keywords' => 'configuration, personnalisation, solutions évolutives, SteamTec, STEAM_TEC, machine sur remorque, solution alternative de nettoyage',
        ],
        'contact' => [
            'title' => 'Contact SteamTec - Nous contacter',
            'description' => 'Contactez SteamTec pour vos besoins en solutions de nettoyage et désherbage éco-responsables.',
            'keywords' => 'contact, SteamTec, STEAM_TEC, devis, information, nettoyage vapeur professionnel, désherbage écologique',
        ],
        'client' => [
            'title' => 'Nos Clients - SteamTec Solutions professionnelles',
            'description' => 'Découvrez nos clients et leurs témoignages. SteamTec accompagne les professionnels du nettoyage et du désherbage.',
            'keywords' => 'clients SteamTec, témoignages, professionnels, nettoyage vapeur professionnel, désherbage professionnel, collectivités',
        ],
        'nettoyage' => [
            'title' => 'Nettoyage vapeur professionnel - SteamTec',
            'description' => 'Solutions de nettoyage vapeur professionnel pour tous types de surfaces. Technologie française éco-responsable.',
            'keywords' => 'nettoyage vapeur, nettoyage vapeur professionnel, nettoyage vapeur toiture, nettoyage vapeur basse pression, nettoyage vapeur façade, nettoyage vapeur terrasse, nettoyage vapeur bois, nettoyage vapeur monuments, nettoyage vapeur bâtiment classé, nettoyage vapeur bâtiment commercial, nettoyage vapeur bâtiment industriel, nettoyage écologique, nettoyage sans produit chimique, nettoyage eau chaude, nettoyeur vapeur, nettoyeur écologique, solution alternative de nettoyage',
        ],
        'desherbage' => [
            'title' => 'Désherbage écologique - SteamTec Solutions Vertes',
            'description' => 'Désherbage écologique à la vapeur sans produits chimiques. Solution respectueuse de l\'environnement.',
            'keywords' => 'désherbage sans produit chimique, désherbage sans produit phytosanitaire, désherbage écologique, désherbage alternatif, désherbage vapeur, désherbage eau chaude, désherbage naturel, désherbage professionnel, désherbage collectivité, désherbage thermique, désherbeur écologique, désherbeur vapeur, désherbeur professionnel, désherbeuse écologique, solution alternative de nettoyage',
        ],
        'materiel' => [
            'title' => 'Matériel de nettoyage et désherbage - SteamTec',
            'description' => 'Matériel professionnel de nettoyage et désherbage à la vapeur. Fabrication française de qualité.',
            'keywords' => 'matériel nettoyage, matériel désherbage, vapeur, fabrication française, fabriqué en France, machine sur remorque, nettoyeur vapeur professionnel, désherbeur professionnel, STEAMTEC, ENTECH',
        ],
        'accessoire' => [
            'title' => 'Accessoires SteamTec - Compléments et pièces détachées',
            'description' => 'Accessoires et pièces détachées pour vos équipements SteamTec. Maintenance et optimisation de vos machines.',
            'keywords' => 'accessoires SteamTec, pièces détachées, maintenance, équipements, STEAMTEC, STEAM_TEC, nettoyeur vapeur, désherbeur écologique',
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
            'description' => 'Fabricant français de solutions éco-responsables de nettoyage et désherbage',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'FR',
                'addressLocality' => 'Votre ville',
                'postalCode' => '00000',
                'streetAddress' => 'Votre adresse'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+33-XX-XX-XX-XX',
                'contactType' => 'customer service',
                'email' => 'contact@steamtec.fr'
            ]
        ]);
    }
} 