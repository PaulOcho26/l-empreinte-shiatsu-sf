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
            $definition = htmlspecialchars($lexicon->getDefinition());

            // Structure sans l'attribut 'title' pour éviter les '...' du navigateur
            $replacement = sprintf(
                '<span class="lexicon-trigger">%s<span class="lexicon-bubble">%s</span></span>',
                $term,
                $definition
            );

            // Remplacement du mot exact, insensible à la casse
            $text = preg_replace('/\b' . preg_quote($term, '/') . '\b/i', $replacement, $text);
        }

        return $text;
    }
}