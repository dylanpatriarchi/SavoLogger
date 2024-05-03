<?php
class SavoLogger {
    private $logFile;
    private $mailer;

    public function __construct($logFile = 'activities.log') {
        $this->logFile = $logFile;
        if (!file_exists($this->logFile)) {
            $fileHandle = fopen($this->logFile, 'w');
            fclose($fileHandle);
        }
        $this->mailer = new SavoMailer();
    }

    public function log($message, $level = 'INFO') {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] [$level]: $message\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        $this->analyzeLog($logMessage);
    }

    private function analyzeLog($logMessage) {
        $sqlInjectionPatterns = [
            'select.*?(from|join|union)',
            'insert.*?into',
            'delete.*?from',
            'update.*?set',
            '\bdrop\b.*?\btable\b',
            '\btruncate\b.*?\btable\b',
            '\bexec\b.*?\bmaster\b',
            '\bxp_cmdshell\b',
            '\bcreate\b.*?\buser\b',
            '\bnet\b.*?\buser\b',
            '0x[0-9a-f]+',
            '[\'"]\s*(or|and)\s+[\'"]\s*\d+\s*[\'"]\s*[=<>]',
        ];

        $xssPatterns = [
            '<script>',
            'onmouseover',
            'onmouseout',
            'onload',
            'onerror',
            'javascript:',
            'alert\(',
            'document\.cookie',
            'eval\(',
        ];

        $patterns = array_merge($sqlInjectionPatterns, $xssPatterns);

        foreach ($patterns as $pattern) {
            if (preg_match("/$pattern/i", $logMessage)) {
                $subject = 'Attività sospette rilevate';
                $body = 'Sono state rilevate possibili attività sospette nei log. Allegato troverai i log sospetti.';
                $this->mailer->send($subject, $body, $this->logFile);
                break;
            }
        }
    }
}
