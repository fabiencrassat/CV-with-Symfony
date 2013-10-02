<?php

// src/Nbs/Bundle/CVBundle/Entity/CurriculumVitae.php

namespace Nbs\Bundle\CVBundle\Entity;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitae
{
	private $Lang;
	private $CV;

	public function __construct($file = 'fabien', $Lang = 'en')
	{
		$this->Lang = $Lang;
		$this->CV = $this->getXmlCurriculumVitae($Lang, $file);
	}

    public function getDropDownLanguages()
    {
        $dropdownItems = array();
        $i = 0;
        foreach ($this->CV->languages->items->item as $value) {
            $dropdownItems[$i++] = array(
                'attribute' => (string) $value['locale'],
                'value' => (string) $value
            );
        };

        $language = array(
            'dropdownTitle' => $this->getValue("languages/title"),
            'items'         => $dropdownItems
        );

        return $language;
    }

    public function getAnchors()
    {
        $anchorsAttribute = $this->CV->xpath("CurriculumVitae/*[attribute::anchor]");
        $anchors = array();
        $i = 0;
        foreach ($anchorsAttribute as $key => $value) {
            $anchors[$i++] = array(
                'href'  => (string) $value['anchor'],
                'title' => $this->getValue("CurriculumVitae/". $value['anchor']. "/AnchorTitle")
            );
        };

        return $anchors;
    }

    public function getIdentity()
    {
        $items_children = $this->CV->CurriculumVitae->identity->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = $item->children();
            foreach ($description as $itemKey => $value) {
                $attributes = (array) $value->attributes();
                if ($value->children()->count() == 0 && count($attributes) == 0) {
                    $items = array_merge($items,
                        array($itemKey => (string) $value)
                    );
                } elseif ($value->children()->{ $this->Lang }) {
                    $items = array_merge($items,
                        array($itemKey => (string) $value->children()->{ $this->Lang })
                    );
                } elseif ($value->children()->count() == 0 && count($attributes) <> 0) {
                    if ($this->Lang == 'en') {
                        $format = $attributes['@attributes']['format'];
                        $age = $this->getAge((string) $value, $format);
                        $items = array_merge($items, array('Age' => $age));
                    } else {
                        // setlocale(LC_TIME, 'fr_FR.UTF8');
                        // setlocale(LC_TIME, 'fr_FR');
                        // setlocale(LC_TIME, 'fr');
                        setlocale(LC_TIME, 'fra_fra');
                        $value = strftime('%d %B %Y', strtotime(date($value)));
                        $items = array_merge($items, array($itemKey => (string) $value));
                    }
                } else {
                    null;
                };
            };
        };

        $identity = array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->identity['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/identity/AnchorTitle"),
        );
        $identity = array_merge($identity, $items);

        return (array) $identity;
    }

    public function getLookingFor()
    {
        $retVal = (string) $this->CV->CurriculumVitae->lookingFor->experience['crossref'];
        $experience = $this->CV->xpath($retVal);
        $experience = (string) $experience[0]->{ $this->Lang };
        $followMe = array(
            'experience'    => $experience,
            'presentation'  => $this->getValue("CurriculumVitae/lookingFor/presentation"),
        );

        return $followMe;
    }

    public function getFollowMe()
    {
        $items_children = $this->CV->CurriculumVitae->followMe->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $attributes = (array) $item->attributes();
            $items = array_merge($items,
                array($key => $attributes['@attributes'])
            );
        };

        $followMe = array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->followMe['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/followMe/AnchorTitle"),
            'follows'       => $items
        );

        return $followMe;
    }

    public function getExperiences()
    {
        $items_children = $this->CV->CurriculumVitae->experiences->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = array();
            foreach ($item as $itemKey => $value) {
                $attributes = (array) $value->attributes();
                $retVal = (count($attributes) > 0) ? $attributes['@attributes']['crossref'] : null;
                //$lineValue = $attributes['crossref'];
                if ($retVal) {
                    # crossref is defined
                    //$ret = $this->CV->xpath($retVal);
                    $description = array_merge($description,
                        array($itemKey => (string) $retVal)
                    );
                } elseif ($value->count() == 0) {
                    # il n'y a plus d'enfant
                    $description = array_merge($description,
                        array($itemKey => (string) $value)
                    );
                } else {
                    # il y a des enfants avec les balises de langues
                    if ($value->{ $this->Lang }->children()->count() == 0) {
                        $lines = (string) $value->{ $this->Lang };
                    } else {
                        $lines = array();
                        $descriptions = $value->{ $this->Lang }->children();
                        foreach ($descriptions as $lineKey => $line) {
                            $lines = array_merge($lines,
                                array((string) $line)
                            );
                        }
                    }
                    $description = array_merge($description,
                        array($itemKey => $lines)
                    );
                }
            };
            $items = array_merge($items,
                array($key => $description)
            );
        };

        return $experiences = array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->experiences['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/experiences/AnchorTitle"),
            'Experiences'   => $items
        );
    }

    public function getSkills()
    {
        $items_children = $this->CV->CurriculumVitae->skills->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = array();
            foreach ($item as $itemKey => $value) {
                if ($value->count() == 0) {
                    # il n'y a plus d'enfant
                    $description = array_merge($description,
                        array($itemKey => (string) $value)
                    );
                } else {
                    # il y a des enfants avec les balises de langues
                    if ($value->{ $this->Lang }) {
                        $lines = (string) $value->{ $this->Lang };
                    } else {
                        # il y a d'autres enfant
                        $lines = array();
                        foreach ($value as $lineKey => $line) {
                            $attributes = (array) $line->attributes();
                            $lineValue = $attributes['@attributes'];
                            if (!$line->{ $this->Lang }) {
                                $title = (string) $line;
                            } else {
                                $title = (string) $line->{ $this->Lang };
                            };
                            $lineValue = array_merge($lineValue,
                                array('title' => $title)
                            );
                            $lines = array_merge($lines,
                                array($lineKey => $lineValue)
                            );
                        }
                    };
                    $description = array_merge($description,
                        array($itemKey => $lines)
                    );
                }
            };
            $items = array_merge($items,
                array($key => $description)
            );
        };

        return array(
            'Anchor'            => (string) $this->CV->CurriculumVitae->skills['anchor'],
            'AnchorTitle'       => $this->getValue("CurriculumVitae/skills/AnchorTitle"),
            'Skills'            => $items
        );
    }

    public function getEducations()
    {
        $items_children = $this->CV->CurriculumVitae->educations->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = array();
            foreach ($item as $itemKey => $value) {
                if ($value->count() == 0) {
                    # il n'y a plus d'enfant
                    $description = array_merge($description,
                        array($itemKey => (string) $value)
                    );
                } else {
                    # il y a des enfants avec les balises de langues
                    if ($value->{ $this->Lang }->children()->count() == 0) {
                        $lines = (string) $value->{ $this->Lang };
                    } else {
                        $lines = array();
                        $descriptions = $value->{ $this->Lang }->children();
                        foreach ($descriptions as $lineKey => $line) {
                            $lines = array_merge($lines,
                                array((string) $line)
                            );
                        }
                    }
                    $description = array_merge($description,
                        array($itemKey => $lines)
                    );
                }
            };
            $items = array_merge($items,
                array($key => $description)
            );
        };

        return array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->educations['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/educations/AnchorTitle"),
            'Educations'    => $items
        );
    }

    public function getLanguageSkills()
    {
        $items_children = $this->CV->CurriculumVitae->languageSkills->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = array();
            foreach ($item as $itemKey => $value) {
                $description = array_merge($description,
                    array($itemKey => $this->getValue("CurriculumVitae/languageSkills/items/". $key ."/". $itemKey))
                );
            };
            $items = array_merge($items, array($key => $description));
        };

        return array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->languageSkills['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/languageSkills/AnchorTitle"),
            'Items'         => $items,
        );
    }

    public function getMiscellaneous()
    {
        $items_children = $this->CV->CurriculumVitae->miscellaneous->items->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $description = array();
            foreach ($item as $itemKey => $value) {
                $description = array_merge($description,
                    array($itemKey => $this->getValue("CurriculumVitae/miscellaneous/items/". $key ."/". $itemKey))
                );
            };
            $items = array_merge($items, array($key => $description));
        };

        return array(
            'Anchor'        => (string) $this->CV->CurriculumVitae->miscellaneous['anchor'],
            'AnchorTitle'   => $this->getValue("CurriculumVitae/miscellaneous/AnchorTitle"),
            'Items'         => $items,
        );
    }

    public function getSociety()
    {
        $items_children = $this->CV->Society->children();
        $items = array();
        foreach ($items_children as $key => $item) {
            $items = array_merge($items,
                array("society/" . $key => (array) $item)
            );
        };

        return array($items);
    }
    
    private function getXmlCurriculumVitae($Lang, $file)
    {
        $pathToFile = __DIR__.'/../Resources/data/'.$file.'.xml';
        if (!is_file($pathToFile)) {
            throw new InvalidArgumentException("The file " . $pathToFile . " does not exist.");
        } else {
            //var_dump($pathToFile);
        }
        
        $XML = simplexml_load_file($pathToFile);

        return $XML;
    }

    private function getAge($birthday, $format)
    {
        if($format <> "mm/dd/yy") {
            throw new InvalidArgumentException("The format " . $format . " is not defined.");
        };
        list($month, $day, $year) = preg_split('[/]', $birthday);
        $today['day'] = date('j');
        $today['month'] = date('n');
        $today['year'] = date('Y');
        $age = $today['year'] - $year;
        if ($today['month'] <= $month) {
            if ($month == $today['month']) {
                if ($day > $today['day'])
                    $age--;
            }
            else
                $age--;
        };

        return $age;
    }

    private function getValue($XPath)
    {
        $value = "";
        // Looking for "lang" attribute
        $values = $this->CV->xpath($XPath ."[attribute::lang='". $this->Lang ."']");
        if (count($values) == 1) {
            foreach ($values as $key => $value) {
                $value = (string) $value;
            }
        }
        // No "Lang" attribute
        elseif (count($values) == 0) {
            // Retrieve the value of the path
            $values = $this->CV->xpath($XPath);
            if (count($values) == 1) {
                foreach ($values as $key => $value) {
                    $value = (string) $value;
                }
            }
            // No value, perhaps chidren?
            elseif (count($value) > 0) {
                foreach ($values as $key => $value) {
                    $value = (string) $value;
                }
            }
            // Something is wrong!
            else {
                var_dump($values);
                throw new InvalidArgumentException("The value does not exist in this path: ". $XPath .".");
            }
        }
        // Something is wrong!
        else {
            var_dump($values);
            throw new InvalidArgumentException("The attribute lang (". $this->Lang .") is not unique in this path: ". $XPath .".");
        }
        return $value;
    }
};