<?php
/**
 * Created by PhpStorm.
 * User: Allan
 * Date: 03/11/2015
 * Time: 15:02
 */

namespace WebBundle\Controller;


use Dr\ReaderBundle\Service\BaseHelper;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller{

    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request){
        // Find last update
        $lastUpdate = $this->getHelper()->getTradingPairRepository()->getLastUpdateTime();

        $chart = new Highchart();
        $chart->chart->renderTo();

        return $this->render('WebBundle:Default:home.html.twig',array(
                'lastUpdate' => $lastUpdate,
                'chart' => $chart,

            )
        );

    }


    /**
     * @return BaseHelper
     */
    protected function getHelper(){
        return $this->get('dr.helper');
    }

}
