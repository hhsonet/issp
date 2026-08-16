<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BangladeshUniversitiesSeeder extends Seeder
{
    private const PUBLIC_URL = 'http://www.ugc-universities.gov.bd/public-universities';
    private const PRIVATE_URL = 'http://www.ugc-universities.gov.bd/private-universities';

    public function run()
    {
        $db = db_connect();
        $db->transStart();

        $public = $this->fetchUniversities(self::PUBLIC_URL, 'Public');
        $private = $this->fetchUniversities(self::PRIVATE_URL, 'Private');
        $rows = array_merge($public, $private);

        $model = model(\App\Models\UniversityModel::class);
        $inserted = 0;
        $updated = 0;
        $unchanged = 0;
        $verifiedAt = date('Y-m-d H:i:s');

        foreach ($rows as $row) {
            $existing = $model->groupStart()
                ->where('LOWER(TRIM(name))', strtolower(trim($row['name'])))
                ->orWhere('short_name', $row['short_name'])
                ->groupEnd()
                ->first();

            $payload = [
                'name' => $row['name'],
                'short_name' => $row['short_name'],
                'type' => $row['type'],
                'website_url' => $row['website_url'],
                'is_active' => 1,
                'source' => 'UGC Bangladesh',
                'source_url' => $row['source_url'],
                'verified_at' => $verifiedAt,
            ];

            if ($existing) {
                $differs = false;
                foreach ($payload as $key => $value) {
                    if ((string) ($existing[$key] ?? '') !== (string) ($value ?? '')) {
                        $differs = true;
                        break;
                    }
                }
                if ($differs) {
                    $model->update($existing['id'], $payload);
                    $updated++;
                } else {
                    $unchanged++;
                }
                continue;
            }

            $model->insert($payload);
            $inserted++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('University synchronization failed.');
        }

        echo "Inserted: {$inserted}, Updated: {$updated}, Unchanged: {$unchanged}" . PHP_EOL;
    }

    private function fetchUniversities(string $url, string $type): array
    {
        $html = @file_get_contents($url);
        if ($html === false) {
            throw new \RuntimeException('Unable to load official university list from ' . $url);
        }

        preg_match_all('/<tr>\s*<td class="text-center">(\d+)\.<\/td>\s*<td><a href="([^"]+)"><span itemprop="name">(.*?)<\/span><\/a><\/td>\s*<td><span itemprop="name">(.*?)<\/span><\/td>\s*<\/tr>/si', $html, $matches, PREG_SET_ORDER);

        $rows = [];
        foreach ($matches as $match) {
            $name = trim(html_entity_decode(strip_tags($match[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $website = trim(html_entity_decode(strip_tags($match[4]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $shortName = $this->deriveShortName($name);
            $rows[] = [
                'name' => preg_replace('/\s+/', ' ', $name),
                'short_name' => $shortName !== $name ? $shortName : null,
                'type' => $type,
                'website_url' => $this->normalizeWebsite($website),
                'source_url' => $url,
            ];
        }

        return $rows;
    }

    private function deriveShortName(string $name): string
    {
        $short = preg_replace('/\s*\((.*?)\)\s*$/', '', trim($name));
        return preg_replace('/\s+/', ' ', $short);
    }

    private function normalizeWebsite(string $website): ?string
    {
        $website = trim($website);
        if ($website === '' || $website === '.' || $website === 'http://' || $website === 'https://') {
            return null;
        }
        return preg_match('#^https?://#i', $website) ? $website : 'http://' . $website;
    }
}
