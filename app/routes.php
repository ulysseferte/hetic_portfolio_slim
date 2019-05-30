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

// Promotions
$app
    ->get(
        '/promotions',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM promotions');
            $promotions = $query->fetchAll();

            // View data
            $viewData = [];
            $viewData['promotions'] = $promotions;

            return $this->view->render($response, 'pages/promotions.twig', $viewData);
        }
    )
    ->setName('promotions')
;

// Promotion
$app
    ->get(
        '/promotions/{year:[0-9]{4}}',
        function($request, $response, $arguments)
        {
            // Fetch promotion
            $prepare = $this->db->prepare(
                'SELECT * FROM promotions WHERE year = :year LIMIT 1'
            );
            $prepare->bindValue('year', $arguments['year']);
            $prepare->execute();
            $promotion = $prepare->fetch();

            if(!$promotion)
            {
                throw new \Slim\Exception\NotFoundException($request, $response);
                
            }
            // Fetch students
            $prepare = $this->db->prepare(
                'SELECT * FROM students WHERE id_promotion = :id_promotion'
            );
            $prepare->bindValue('id_promotion', $promotion->id);
            $prepare->execute();
            $students = $prepare->fetchAll();

            if(!$promotion)
            {
                throw new \Slim\Exception\NotFoundException($request, $response);
            }
            // View data
            $viewData = [];
            $viewData['title'] = 'P'.$promotion->year;
            $viewData['promotion'] = $promotion;
            $viewData['students'] = $students;

            return $this->view->render($response, 'pages/promotion.twig', $viewData);
        }
    )
    ->setName('promotion')
;

// Random student
$app
    ->get(
        '/students/random',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM students ORDER BY RAND() LIMIT 1');
            $student = $query->fetch();

            $url = $this->router->pathFor('student', [ 'slug' => $student->slug ]);
            
            return $response->withRedirect($url);
        }
    )
    ->setName('random_student')
;

// Student
$app
    ->get(
        '/students/{slug:[a-z_-]+}',
        function($request, $response, $arguments)
        {
            $prepare = $this->db->prepare(
                'SELECT * FROM students WHERE slug = :slug LIMIT 1'
            );
            $prepare->bindValue('slug', $arguments['slug']);
            $prepare->execute();
            $student = $prepare->fetch();

             // Fetch promotion
             $prepare = $this->db->prepare(
                'SELECT * FROM promotions WHERE id = :id LIMIT 1'
            );
            $prepare->bindValue('id', $student->id_promotion);
            $prepare->execute();
            $promotion = $prepare->fetch();

            // View data
            $viewData = [];
            $viewData['student'] = $student;
            $viewData['promotion'] = $promotion;

            return $this->view->render($response, 'pages/student.twig', $viewData);
        }
    )
    ->setName('student')
;

// Projects

$app
    ->get(
        '/projects',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM projects');
            $projects = $query->fetchAll();

            // View data
            $viewData = [];
            $viewData['projects'] = $projects;

            return $this->view->render($response, 'pages/projects.twig', $viewData);
        }
    )
    ->setName('projects');

// Project

$app
    ->get(
        '/project/{id}',
        function($request, $response, $arguments)
        {
            // Fetch project
            $prepare = $this->db->prepare(
                'SELECT * FROM projects WHERE id = :id LIMIT 1'
            );
            $prepare->bindValue('id', $arguments['id']);
            $prepare->execute();
            $project = $prepare->fetch();
    )