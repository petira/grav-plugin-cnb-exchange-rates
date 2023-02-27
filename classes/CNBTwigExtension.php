<?php
namespace Grav\Plugin;
use Grav\Common\Grav;
use Twig_Extension;
class CNBTwigExtension extends Twig_Extension
{
    public function __construct()
    {
        $this->grav = Grav::instance();
    }
    public function getName()
    {
        return 'CNBTwigExtension';
    }
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('cnb', [$this, 'getCNB'])
        ];
    }
    public function CNB($cnb, $file, $language) {
        $row = 0;
        if (($file = fopen($file, 'r')) !== FALSE) {
            while (($data = fgetcsv($file, 0, '|')) !== FALSE) {
                $row++;
                if ($row > 2) {
                    $code = $data[3];
                    $cnb[$code]['country_' . $language] = $cnb[$code]['country'] = $data[0];
                    $cnb[$code]['currency_' . $language] = $cnb[$code]['currency'] = $data[1];
                    $cnb[$code]['amount_' . $language] = $cnb[$code]['amount'] = $data[2];
                    $cnb[$code]['code_' . $language] = $cnb[$code]['code'] = $data[3];
                    $cnb[$code]['rate_' . $language] = $cnb[$code]['rate'] = $data[4];
                }
            }
            fclose($file);
            return $cnb;
        }
    }
    public function getCNB() {
        $cnb = [];
        switch ($this->grav['config']->get('plugins.cnb-exchange-rates.sequence')) {
            case 'en_cs':
                $languages = ['cs', 'en']; // the last one overwrites the first one
                break;
            case 'cs_en':
                $languages = ['en', 'cs']; // the last one overwrites the first one
                break;
            case 'en':
                $languages = ['en'];
                break;
            case 'cs':
                $languages = ['cs'];
                break;
        }
        foreach($languages as $language) {
            $file = $this->grav['config']->get('plugins.cnb-exchange-rates.source_' . $language);
            $cnb = self::CNB($cnb, $file, $language);
        }
        return $cnb;
    }
}
?>
