<?php

class ActualiteController {
    private $articles = [
        [
            'id' => 1,
            'titre' => 'Avoir une location dans les grandes villes, Est-ce encore possible ?',
            'accroche' => 'Derrière les chiffres de l\'enseignement supérieur se cache une réalité plus rude...',
            'contenu' => 'Derrière les chiffres de l\'enseignement supérieur se cache une réalité plus rude. Selon l\'Observatoire de la vie étudiante, près de 70 % des étudiants vivent hors du domicile familial, et pour beaucoup, cela signifie entrer sur le marché locatif, souvent pour la première fois. Mais ce marché est saturé.

            Dans les métropoles universitaires comme Paris, Lyon, ou Bordeaux, les loyers atteignent des sommets. À Paris, il faut compter en moyenne 850 € pour un studio, un montant que peu d\'étudiants peuvent assumer seuls. À Lille, un T1 se loue en moyenne 550 €, charges non comprises. « La demande est telle que certains propriétaires sélectionnent les dossiers en quelques heures », témoigne Clara, 20 ans, étudiante en licence de droit à Toulouse.'
        ],
        [
            'id' => 2,
            'titre' => 'Des alternatives parfois coûteuses et moindres pour les Étudiants en 2025',
            'accroche' => 'Plusieurs solutions coexistent. Les résidences universitaires du CROUS...',
            'contenu' => 'Plusieurs solutions coexistent. Les résidences universitaires du CROUS, subventionnées par l\'État, restent l\'option la plus économique : entre 150 et 400 € mensuels, selon la région Ile de France. Mais avec moins de 180 000 places disponibles pour plus de 2,5 millions d\'étudiants, l\'accès reste très limité. Résultat : les files d\'attente s\'allongent dès le printemps.

            Autre alternative : les résidences étudiantes privées. Plus confortables, mieux situées, elles affichent aussi des loyers plus élevés, entre 500 et 800 € selon les prestations. De plus en plus de jeunes optent également pour la colocation, parfois par choix de vie, souvent par nécessité. « À trois, on a pu se loger dans un grand appartement à Grenoble pour 450 € chacun, charges comprises. C\'était impensable seul », explique Karim, 22 ans.

            Certaines associations encouragent également l\'hébergement intergénérationnel, où un étudiant est logé à tarif réduit chez une personne âgée, en échange de services ou d\'une présence rassurante.'
        ]
    ];

    public function liste() {
        $articles = $this->articles;
        include __DIR__ . '/../Views/actualite.php';
    }

public function detail($id) {
    $id = (int)$id; 

    $article = null;
    foreach ($this->articles as $art) {
        if ($art['id'] === $id) {
            $article = $art;
            break;
        }
    }

    if (!$article) {
        header('HTTP/1.0 404 Not Found');
        include __DIR__ . '/../Views/404.php';
        return;
    }

    include __DIR__ . '/../Views/article_detail.php';
}
}
