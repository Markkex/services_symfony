<?php 

namespace App\service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper 
{
    private $markdownParser;
    private $cache;
    private $isDebug;
    private $markdownLogger;

    public function __construct(
        MarkdownParserInterface $markdownParser, 
        CacheInterface $cache, 
        bool $isDebug,
        LoggerInterface $mdLogger,
    )
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->markdownLogger = $mdLogger;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'cat') !== false) {
            $this->markdownLogger->info('Meow!');
        }

        if($this->isDebug) {
            return $this->markdownParser->transformMarkdown($source);
        }

        return $this->cache->get('markdown_'.md5($source), function() use ($source) {
            return $this->markdownParser->transformMarkdown($source);
        });
    }
}