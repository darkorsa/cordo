<?php

use App\Register;
use Cordo\Core\UI\Locale;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;

$locale = Locale::get($container, defined('STDIN'));

$translator = new Translator($locale);
$translator->addLoader('yaml', new YamlFileLoader());
$translator->setFallbackLocales(['en']);

Register::registerTranslations($translator, $container);

return $translator;
