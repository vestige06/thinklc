<?php

class ArticleViewModel extends ViewModel {

    public $viewFields = array(
        'Article' => array('id', 'uid', 'expired', 'COUNT(Article.id)' => 'count', '_type' => 'LEFT'),
        'Member' => array('uname', '_on' => 'Article.uid=Member.id'),
    );

}