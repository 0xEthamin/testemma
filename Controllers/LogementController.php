<?php
require_once __DIR__ . '/../Models/LogementModel.php';
require_once __DIR__ . '/../Models/FavorisModel.php';

class LogementController {
    private $pdo;
    private $logementModel;
    private $favorisModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->logementModel = new LogementModel($pdo);
        $this->favorisModel = new FavorisModel($pdo);
    }

    public function accueil() {
        $this->renderView('accueil');
    }

    public function rechercher() {
    try {
        // Récupération des paramètres de recherche
        $searchTerm = trim($_GET['search'] ?? '');
        $zone = trim($_GET['zone'] ?? '');
        $region = trim($_GET['region'] ?? '');
        $budget = isset($_GET['budget']) && is_numeric($_GET['budget']) ? (float)$_GET['budget'] : null;
        $sort = $_GET['sort'] ?? 'name_asc';

        // Préparation des filtres
        $filters = [
            'search' => $searchTerm,
            'zone' => $zone,
            'region' => $region,
            'budget' => $budget,
            'sort' => $sort
        ];

        // Configuration de la pagination
        $logementsParPage = 30;
        $pageActuelle = max(1, (int)($_GET['pageNum'] ?? 1));
        $offset = ($pageActuelle - 1) * $logementsParPage;

        // Récupération des données nécessaires
        $zones = $this->logementModel->getZones();
        $regions = $this->logementModel->getRegions();

        // Récupération des logements filtrés
        $allLogements = $this->logementModel->getLogements($filters, 0, 0);
        $totalLogements = count($allLogements);
        $totalPages = max(1, ceil($totalLogements / $logementsParPage));
        $logements = array_slice($allLogements, $offset, $logementsParPage);

        // Récupération des favoris si utilisateur connecté
        $favoris = [];
        if (isset($_SESSION['user'])) {
            $favoris = $this->favorisModel->getFavorisByUser($_SESSION['user']['id']);
        }

        // Construction de la query string pour la pagination
        $queryString = '';
        foreach (['zone', 'region', 'search', 'budget', 'sort'] as $key) {
            if (!empty($filters[$key])) {
                $queryString .= '&' . $key . '=' . urlencode($filters[$key]);
            }
        }

        // Affichage de la vue (la même que pour list())
        $this->renderView('logement-liste', [
            'logements' => $logements,
            'zones' => $zones,
            'regions' => $regions,
            'filters' => $filters,
            'pageActuelle' => $pageActuelle,
            'totalPages' => $totalPages,
            'queryString' => $queryString,
            'favoris' => $favoris,
            'isLoggedIn' => isset($_SESSION['user'])
        ]);

    } catch (PDOException $e) {
        $this->renderError("Erreur lors de la recherche : " . $e->getMessage());
    }
}

    public function detail() {
        if (!isset($_GET['id'])) {
            $this->renderError("ID du logement non spécifié.");
            return;
        }

        $id = intval($_GET['id']);
        $logement = $this->logementModel->getLogementById($id);

        if (!$logement) {
            $this->renderError("Logement non trouvé.");
            return;
        }

        $isFavori = false;
        if (isset($_SESSION['user'])) {
            $isFavori = $this->favorisModel->isFavori($_SESSION['user']['id'], $id);
        }

        $this->renderView('annonce', [
            'logement' => $logement,
            'isFavori' => $isFavori
        ]);
    }

    public function list() {
        try {
            $filters = [
                'zone' => $_GET['zone'] ?? '',
                'region' => $_GET['region'] ?? '',
                'search' => $_GET['search'] ?? '',
                'budget' => $_GET['budget'] ?? '',
                'sort' => $_GET['sort'] ?? 'name_asc',
            ];

            $logementsParPage = 30;
            $pageActuelle = max(1, (int)($_GET['pageNum'] ?? 1));
            $offset = ($pageActuelle - 1) * $logementsParPage;

            $zones = $this->logementModel->getZones();
            $regions = $this->logementModel->getRegions();

            $allLogements = $this->logementModel->getLogements($filters, 0, 0);
            $totalLogements = count($allLogements);
            $totalPages = max(1, ceil($totalLogements / $logementsParPage));
            $logements = array_slice($allLogements, $offset, $logementsParPage);

            $favoris = [];
            if (isset($_SESSION['user'])) {
                $favoris = $this->favorisModel->getFavorisByUser($_SESSION['user']['id']);
            }

            $queryString = '';
            foreach (['zone', 'region', 'search', 'budget', 'sort'] as $key) {
                if (!empty($filters[$key])) {
                    $queryString .= '&' . $key . '=' . urlencode($filters[$key]);
                }
            }

            $this->renderView('logement-liste', [
                'logements' => $logements,
                'zones' => $zones,
                'regions' => $regions,
                'filters' => $filters,
                'pageActuelle' => $pageActuelle,
                'totalPages' => $totalPages,
                'queryString' => $queryString,
                'favoris' => $favoris,
                'isLoggedIn' => isset($_SESSION['user'])
            ]);
        } catch (PDOException $e) {
            $this->renderError("Erreur lors de la récupération des logements : " . $e->getMessage());
        }
    }

    private function renderView(string $viewName, array $data = []) {
        extract($data);
        require __DIR__ . '/../Views/' . $viewName . '.php';
    }

    private function renderError(string $message) {
        $this->renderView('error', ['message' => $message]);
    }
}
