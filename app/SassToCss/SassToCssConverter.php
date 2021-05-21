<?php

namespace App\SassToCss;

class SassToCssConverter {

    /**
     * Conversion d'un fichier SASS en CSS
     *
     * @param string $sassFile SASS file path
     * @param string $cssFile CSS file path
     */
	public function convert(string $sassFile, string $cssFile) {

		$cssClasses = null;

		try {
			$cssClasses = $this->getCssClassesFromSassFile($sassFile);
			
		} catch (Exception $e) {
			echo "Une erreur est survenue lors de la génération du tableau de classes CSS. Erreur: " . $e->getMessage();
		}

		if (empty($cssClasses)) {
			die("Le fichier sass ne contient aucunes classes.");
		}

		try {
			$this->generateCssFileFromArray($cssFile, $cssClasses);
			
		} catch (\Exception $e) {
			echo "Une erreur est survenue lors de la génération du fichier CSS. Erreur: " . $e->getMessage();
		}

		echo "Le fichier sass " . $sassFile . " a bien été converti en css " . $cssFile . ".\n";
	}

    /**
     * Récupère les classes SASS d'un fichier et retourne un tableau de classes
     *
     * @param string $file SASS file path
     *
     * @return array
     */
	private function getCssClassesFromSassFile(string $file): array {

		$convertedCss = [];

		$handle = fopen($file, "r");
		if ($handle) {
			$currentClass = '';
			$currentArbo = '';

		    while (($line = fgets($handle)) !== false) {
                // Premier charactère de la ligne qui va nous permetttre de récupérer le niveau d'arborescence
                $firstCharacter = substr(trim($line), 0, 1);
                $arbo = '';

                // Lignes ignorées
                if (str_contains($line, '}') || // Accolades fermantes
                    (strlen($line) > 0 && strlen(trim($line)) == 0) || // Lignes vides
                    $firstCharacter == '{') { // Accolades ouvrantes en début de ligne
                    continue;
                }

				// Récupération du niveau d'arborescence
		    	if (!empty($firstCharacter) && in_array($firstCharacter, ['.', '&'])) {
					$arbo = explode($firstCharacter, $line)[0];
		    	}

		  		// Classe
		    	if (str_contains($line, '.') && !empty($currentClass)) {

					// Même niveau
		    		if ($arbo == $currentArbo) {
		    		    // On enlève la classe du niveau supérieur
						$classes = explode(' ', $currentClass);
						array_pop($classes);
						$currentClass = implode(' ', $classes);

					// Niveau inférieur
		    		} elseif (strlen($currentArbo) > strlen($arbo)) {
						$currentClass = '';
		    		}
					$currentArbo = $arbo;
		    	}

				// Plusieurs classe
		    	if (str_contains($line, '.') && !empty($currentClass)) {

		    		$currentClass = $currentClass . ' .' . preg_replace('/[^A-Za-z0-9\-]/', '', $line);
				// Classe unique
				} elseif (str_contains($line, '.')) {

					$currentClass = '.' . preg_replace('/[^A-Za-z0-9\-]/', '', $line);
					$convertedCss[$currentClass] = null;
				// Effets
		        } elseif (str_contains($line, '&')) {

		        	$currentClass = $currentClass . ':' . preg_replace('/[^A-Za-z0-9\-]/', '', $line);
				// Ligne de style
		        } else {
		        	$convertedCss[$currentClass][] = trim($line, ' \n\r\t\v\0');
		        }
		    }

		    fclose($handle);

		    return $convertedCss;

		} else {
		    echo "Une erreur est survenue lors de l'ouverture du fichier.";

		    return [];
		}
	}

	/**
     * Génère un fichier CSS à partir d'une tableau de classes
     *
     * @param string $file SASS file path
     * @param array $arrayCss Tableau de classes CSS
     */
	private function generateCssFileFromArray(string $file, array $arrayCss) {

	    $fp = fopen($file, 'w');

	    foreach ($arrayCss as $class => $styles) {

			fwrite($fp, $class . ' {');
			fwrite($fp, "\n");

	    	foreach ($styles as $style) {
				fwrite($fp, "\t" . $style);
			}

			fwrite($fp, '}');
			fwrite($fp, "\n");
	    }

		fclose($fp);
	}
}