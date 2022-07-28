<?php

namespace IHORCHYSHKALA\Dump;

use Illuminate\Support\Debug\Dumper;
use Symfony\Component\VarDumper\VarDumper as VarDumper;
use Illuminate\Foundation\Application as Laravel;
use System\Classes\PluginBase;

/**
 * Dump Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Twig Dump + all Laravel versions support',
            'description' => 'Twig function d() that recursively dump passed variables only if app.debug is true',
            'author'      => 'IHORCHYSHKALA',
            'icon'        => 'icon-code'
        ];
    }

    /**
     * Register new Twig function
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        return  [
             'functions' => [
                    'd' => $this->getDumpCallback(),
             ]
        ];
    }

    /**
     * Dump function
     *
     * @return \Closure
     */
    private function getDumpCallback(): \Closure
    {
        return static function() {
              if (\Config::get('app.debug') !== true) {
                   return '';
              }

              return array_map(function($x) {
                    $dumper = version_compare(Laravel::VERSION, '6', '<') ? Dumper::class : VarDumper::class;
                    app($dumper)->dump($x);
                 }, funct_get_args()); 
        }
    }