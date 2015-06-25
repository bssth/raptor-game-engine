<?php

class sandboxDriver {

    public function actionIndex()
    {
        $sb = new QueryBuilder;
        $sb->select('*')->from('users')->display();
    }

}
