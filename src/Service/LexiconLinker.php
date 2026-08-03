<?php

namespace App\Service;

use App\Repository\LexiconRepository;

class LexiconLinker
{
    public function __construct(private LexiconRepository $lexiconRepository) 
    {
    }

    public function linkTerms(string $text): string
{
    $terms = $this->lexiconRepository->findAll();

    foreach ($terms as $lexicon) {
        $term = $lexicon->getTerm();
        $slug = $lexicon->getSlug(); // On récupère le slug pour le lien
        $definition = htmlspecialchars($lexicon->getDefinition());

        // Remplacement par un lien interactif (A) au lieu d'un simple SPAN
        $replacement = sprintf(
            '<a href="/lexique/%s" class="lexicon-trigger group relative border-b border-dotted border-zen-forest">%s<span class="lexicon-bubble">%s</span></a>',
            $slug,
            $term,
            $definition
        );

        $text = preg_replace('/\b' . preg_quote($term, '/') . '\b/i', $replacement, $text);
    }

    return $text;

    }
}