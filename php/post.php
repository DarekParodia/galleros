<?php 

class Post {
    private int $id;
    private string $title;
    private int $likes;
    private int $dislikes;
    private Gallery $gallery;

    public function __construct(int $id = null) {

    }
}