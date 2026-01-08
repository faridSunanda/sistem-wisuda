<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleTranslateService
{
    private $translator;
    private $cacheDuration = 86400; // 24 jam
    
    /**
     * Get translator instance (lazy loading)
     */
    private function getTranslator()
    {
        if ($this->translator === null) {
            try {
                $this->translator = new GoogleTranslate();
                $this->translator->setSource('id'); // Bahasa Indonesia
                $this->translator->setTarget('en'); // Bahasa Inggris
                
                // Set proxy/options jika perlu
                $this->setTranslatorOptions();
            } catch (\Exception $e) {
                Log::error('Failed to initialize GoogleTranslate: ' . $e->getMessage());
                throw $e;
            }
        }
        
        return $this->translator;
    }
    
    private function setTranslatorOptions()
    {
        // Options untuk mengurangi blokir
        $options = [
            'timeout' => 10,
            'verify' => false,
        ];
        
        // Jika perlu proxy (opsional)
        // $options['proxy'] = 'http://proxy.unwahas.ac.id:8080';
        
        $this->getTranslator()->setOptions($options);
    }
    
    /**
     * Translate certificate name with fallback
     */
    public function translateCertificate(string $indonesianName): string
    {
        if (empty(trim($indonesianName))) {
            return '';
        }
        
        // Clean input
        $indonesianName = trim($indonesianName);
        
        // Cache key
        $cacheKey = 'google_trans_' . md5($indonesianName);
        
        // Try cache first
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            // Attempt 1: Direct translation
            $translated = $this->translateWithRetry($indonesianName);
            
            // Validate translation
            if ($this->isValidTranslation($translated, $indonesianName)) {
                $formatted = $this->formatCertificateName($translated);
                Cache::put($cacheKey, $formatted, $this->cacheDuration);
                return $formatted;
            }
            
        } catch (Exception $e) {
            Log::warning('Google Translate failed: ' . $e->getMessage());
        }
        
        // Fallback to glossary if Google Translate fails
        $fallback = $this->glossaryTranslation($indonesianName);
        Cache::put($cacheKey, $fallback, $this->cacheDuration);
        
        return $fallback;
    }
    
    /**
     * Translate with retry mechanism
     */
    private function translateWithRetry(string $text, int $maxRetries = 2): string
    {
        $retries = 0;
        
        while ($retries <= $maxRetries) {
            try {
                // Random delay to avoid rate limiting
                if ($retries > 0) {
                    sleep(rand(1, 3));
                }
                
                return $this->getTranslator()->translate($text);
                
            } catch (Exception $e) {
                $retries++;
                
                if ($retries > $maxRetries) {
                    throw $e;
                }
                
                // Try changing URL on retry
                $urls = [
                    'http://translate.google.com/translate_a/single',
                    'http://translate.googleapis.com/translate_a/single',
                ];
                
                $this->getTranslator()->setUrl($urls[$retries % count($urls)]);
            }
        }
        
        return $text;
    }
    
    /**
     * Validate translation result
     */
    private function isValidTranslation(string $translated, string $original): bool
    {
        // Check if translation is same as original
        if (strtolower($translated) === strtolower($original)) {
            return false;
        }
        
        // Check if translation is too short
        if (strlen($translated) < strlen($original) * 0.3) {
            return false;
        }
        
        // Check if contains weird characters
        if (preg_match('/[^\x20-\x7E]/', $translated)) {
            return false;
        }
        
        // Check if looks like error message
        $errorPatterns = [
            '/could not translate/i',
            '/translation failed/i',
            '/error/i',
            '/exception/i',
        ];
        
        foreach ($errorPatterns as $pattern) {
            if (preg_match($pattern, $translated)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Format certificate name properly
     */
    private function formatCertificateName(string $name): string
    {
        // Clean up
        $name = trim($name);
        $name = preg_replace('/\s+/', ' ', $name);
        
        // Fix common Google Translate issues
        $fixes = [
            '/\bCertificat\b/i' => 'Certificate',
            '/\bTrainning\b/i' => 'Training',
            '/\bWorkshopp\b/i' => 'Workshop',
            '/\bSeminarr\b/i' => 'Seminar',
            '/\bCompetitionn\b/i' => 'Competition',
            '/\bParticipate\b/i' => 'Participation',
            '/\s+\./' => '.',
        ];
        
        foreach ($fixes as $pattern => $replacement) {
            $name = preg_replace($pattern, $replacement, $name);
        }
        
        // Title case for certificate names
        return $this->toTitleCase($name);
    }
    
    /**
     * Convert to title case (preserving acronyms)
     */
    private function toTitleCase(string $text): string
    {
        $smallWords = ['a', 'an', 'and', 'as', 'at', 'but', 'by', 'for', 'from', 
                      'in', 'into', 'nor', 'of', 'on', 'or', 'so', 'the', 'to', 
                      'up', 'via', 'with', 'yet'];
        
        $words = explode(' ', $text);
        
        foreach ($words as $i => $word) {
            $lowerWord = strtolower($word);
            
            // Preserve acronyms
            if (preg_match('/^[A-Z]{2,}$/', $word) || 
                in_array($lowerWord, ['php', 'html', 'css', 'js', 'api', 'iot', 'ai'])) {
                $words[$i] = strtoupper($word);
            }
            // Capitalize first word or important words
            elseif ($i === 0 || !in_array($lowerWord, $smallWords)) {
                $words[$i] = ucfirst($lowerWord);
            } else {
                $words[$i] = $lowerWord;
            }
        }
        
        $result = implode(' ', $words);
        
        // Ensure certificate terminology is at the end
        return $this->reorderCertificateName($result);
    }
    
    /**
     * Reorder to put certificate type at the end
     */
    private function reorderCertificateName(string $name): string
    {
        $certificateWords = ['Certificate', 'Training', 'Workshop', 'Seminar', 
                           'Webinar', 'Award', 'Competition', 'Diploma'];
        
        $words = explode(' ', $name);
        
        foreach ($certificateWords as $certWord) {
            $index = array_search($certWord, $words);
            if ($index !== false && $index > 0) {
                // Move to end
                unset($words[$index]);
                $words = array_values($words);
                $words[] = $certWord;
                break;
            }
        }
        
        return implode(' ', $words);
    }
    
    /**
     * Fallback glossary translation
     */
    private function glossaryTranslation(string $text): string
    {
        $glossary = [
            'sertifikat' => 'Certificate',
            'pelatihan' => 'Training',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'webinar' => 'Webinar',
            'penghargaan' => 'Award',
            'kompetensi' => 'Competency',
            'partisipasi' => 'Participation',
            'kehadiran' => 'Attendance',
            'magang' => 'Internship',
            'organisasi' => 'Organization',
            'bahasa' => 'Language',
            'inggris' => 'English',
            'lomba' => 'Competition',
            'juara' => 'Champion',
            'nasional' => 'National',
            'internasional' => 'International',
            'teknologi' => 'Technology',
            'pemrograman' => 'Programming',
            'aplikasi' => 'Application',
            'web' => 'Web',
            'mobile' => 'Mobile',
            'jaringan' => 'Network',
            'database' => 'Database',
            'akademik' => 'Academic',
            'non-akademik' => 'Non-Academic',
            'kepemimpinan' => 'Leadership',
            'komunikasi' => 'Communication',
        ];
        
        $translated = $text;
        
        // Replace known words
        foreach ($glossary as $id => $en) {
            $translated = preg_replace("/\b{$id}\b/i", $en, $translated);
        }
        
        // Format
        return $this->toTitleCase($translated);
    }
    
    /**
     * Batch translate multiple names
     */
    public function translateBatch(array $names): array
    {
        $results = [];
        
        foreach ($names as $key => $name) {
            $results[$key] = $this->translateCertificate($name);
        }
        
        return $results;
    }
    
    /**
     * Test connection to Google Translate
     */
    public function testConnection(): array
    {
        $testText = 'Sertifikat pelatihan';
        
        try {
            $startTime = microtime(true);
            $result = $this->getTranslator()->translate($testText);
            $endTime = microtime(true);
            
            return [
                'success' => true,
                'test_text' => $testText,
                'result' => $result,
                'response_time' => round(($endTime - $startTime) * 1000, 2) . 'ms',
                'message' => 'Google Translate is working'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Google Translate is not accessible. Using fallback glossary.'
            ];
        }
    }
    
    /**
     * Clear translation cache
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}