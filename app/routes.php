<?php

// Home
$app
    ->get(
        '/',
        function($request, $response)
        {
            // View data
            $viewData = [];

            return $this->view->render($response, 'pages/home.twig', $viewData);
        }
    )
    ->setName('home')
;




// Projects

$app
    ->get(
        '/projects',
        function($request, $response)
        {
            // View data
            $viewData = [];

            return $this->view->render($response, 'pages/projects.twig', $viewData);
        }
    )
    ->setName('projects');


;