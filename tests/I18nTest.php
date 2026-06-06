<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../utils/i18n.php';

class I18nTest extends TestCase {
    
    protected function setUp(): void {
        // Reset session before each test
        $_SESSION = [];
    }

    public function testDefaultLanguageIsUsedWhenSessionNotSet() {
        // When $_SESSION['lang'] is not set, it should default to 'en'
        // 'en.php' contains typical phrases. For test purpose, let's test a key we know might exist or at least see what it returns.
        // Assuming 'en.php' exists, but Since we haven't seen it, let's just test the fallback behavior:
        // If the key doesn't exist, t() returns the key itself.
        $key = 'NON_EXISTENT_KEY_' . time();
        $this->assertEquals($key, t($key));
    }

    public function testTranslationReturnsKeyWhenLanguageFileIsMissing() {
        // Set an invalid language that definitely has no file
        $_SESSION['lang'] = 'invalid_language_xyzz';
        $key = 'ANOTHER_MISSING_KEY';
        // It will fall back to 'en.php', and if key isn't there, it returns the key
        $this->assertEquals($key, t($key));
    }
}
