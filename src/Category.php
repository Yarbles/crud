<?php

    class Category
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {

         $this->name = $name;
         $this->id = $id;

        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }


        function getId()
        {
            return $this->id;
        }

        function getTasks()
        {
            $tasks = Array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE
                category_id = {$this->getId()};");
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $new_Task = new Task($description, $id, $category_id);
                array_push($tasks, $new_Task);
            }
            return $tasks;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO categories (name)
                VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category){
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);

            }
            return $categories;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category){
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                    $found_category = $category;
                }
            }
            return $found_category;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE category_id = {$this->getId()};");
        }


    }

 ?>
