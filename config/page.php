<?php

return array(

            'blog' => array(
                'model'     => 'Model_Page',
                'title'     => 'Article',
                'related'   => false,
                'comments'  => true
            ),

            'report' => array(
                'model'     => 'Model_Report',
                'title'     => 'Report',
                'related'   => array('report'),
                'comments'  => true
            ),

            'anime' => array(
                'model'     => 'Model_Anime',
                'title'     => 'Anime',
                'related'   => array('anime'),
                'comments'  => false
            ),

);