<?php
class LogementModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getZones(): array {
        return $this->pdo->query("SELECT DISTINCT Zone FROM logements ORDER BY Zone ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getRegions(): array {
        return $this->pdo->query("SELECT DISTINCT regions FROM logements ORDER BY regions ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getLogementsNonValidés(): array {
        $stmt = $this->pdo->query("SELECT id, Nom, Description, Adresse FROM logements WHERE est_valide = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildWhereClause(array $filters): array {
        $where = [];
        $params = [];

        if (!empty($filters['zone'])) {
            $where[] = "Zone = :zone";
            $params[':zone'] = $filters['zone'];
        }

        if (!empty($filters['region'])) {
            $where[] = "regions = :region";
            $params[':region'] = $filters['region'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(Nom LIKE :search OR Description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!isset($filters['include_invalid']) || !$filters['include_invalid']) {
            $where[] = "est_valide = 1";
        }

        $condition = $where ? "WHERE " . implode(" AND ", $where) : "";

        return ['condition' => $condition, 'params' => $params];
    }

    public function countLogements(array $filters): int {
        $sql = "SELECT * FROM logements WHERE est_valide = 1";
        $params = [];

        if (!empty($filters['zone'])) {
            $sql .= " AND Zone = :zone";
            $params[':zone'] = $filters['zone'];
        }

        if (!empty($filters['region'])) {
            $sql .= " AND regions = :region";
            $params[':region'] = $filters['region'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (Nom LIKE :search OR Adresse LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        $logements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Filtrage budget en PHP en utilisant uniquement le prix minimal
        if (!empty($filters['budget'])) {
            $budgetMax = (float) $filters['budget'];
            $logements = array_filter($logements, function ($logement) use ($budgetMax) {
                $prixRaw = $logement['prix'] ?? '';
                $prixArray = explode(',', trim($prixRaw, '{}'));
                $prixArray = array_map('floatval', $prixArray);
                $prixArray = array_filter($prixArray, fn($p) => $p > 0);
                if (empty($prixArray)) return false;
                $minPrix = min($prixArray);
                return $minPrix <= $budgetMax;
            });
        }

        return count($logements);
    }

    public function getLogements(array $filters, int $limit, int $offset): array {
        $sql = "SELECT * FROM logements WHERE est_valide = 1";
        $params = [];

        if (!empty($filters['zone'])) {
            $sql .= " AND Zone = :zone";
            $params[':zone'] = $filters['zone'];
        }

        if (!empty($filters['region'])) {
            $sql .= " AND regions = :region";
            $params[':region'] = $filters['region'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (Nom LIKE :search OR Adresse LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY id ASC";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        $logements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Filtrage budget uniquement sur le prix minimal
        if (!empty($filters['budget'])) {
            $budgetMax = (float) $filters['budget'];
            $logements = array_filter($logements, function ($logement) use ($budgetMax) {
                $prixRaw = $logement['prix'] ?? '';
                $prixArray = explode(',', trim($prixRaw, '{}'));
                $prixArray = array_map('floatval', $prixArray);
                $prixArray = array_filter($prixArray, fn($p) => $p > 0);
                if (!$prixArray) return false;
                $minPrix = min($prixArray);
                return $minPrix <= $budgetMax;
            });
        }

        // Tri en PHP
        $sort = $filters['sort'] ?? 'name_asc';
        if ($sort === 'name_asc') {
            usort($logements, fn($a, $b) => strcmp($a['Nom'], $b['Nom']));
        } elseif ($sort === 'name_desc') {
            usort($logements, fn($a, $b) => strcmp($b['Nom'], $a['Nom']));
        } elseif ($sort === 'price_asc' || $sort === 'price_desc') {
            usort($logements, function ($a, $b) use ($sort) {
                $getMinPrix = function ($logement) {
                    $prixArray = explode(',', trim($logement['prix'] ?? '', '{}'));
                    $prixArray = array_map('floatval', $prixArray);
                    $prixArray = array_filter($prixArray, fn($p) => $p > 0);
                    return $prixArray ? min($prixArray) : PHP_INT_MAX;
                };
                $pa = $getMinPrix($a);
                $pb = $getMinPrix($b);
                if ($pa === $pb) return 0;
                if ($sort === 'price_asc') return $pa <=> $pb;
                return $pb <=> $pa;
            });
        }

        // Pagination en PHP
        if ($limit > 0) {
            $logements = array_slice($logements, $offset, $limit);
        }

        return $logements;
    }

    public function getLogementById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM logements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
