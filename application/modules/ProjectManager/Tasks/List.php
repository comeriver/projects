<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    ProjectManager_Tasks_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class ProjectManager_Tasks_List extends ProjectManager_Tasks_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'To do';   

    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
      $this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		if( ! empty( $_GET['goals_id'] ) )
		{
			$this->_dbWhereClause['goals_id'] = $_GET['goals_id'];
            if( ! $goalInfo = ProjectManager_Goals::getInstance()->selectOne( null, array( 'goals_id' => $_GET['goals_id'] ) ) )
            {
                //$this->setViewContent(  '' . self::__( '<div class="badnews">Goal for this task cannot be found</div>' ) . '', true  );
                //return false;
            }
            if( ! $postData = Application_Article_Abstract::loadPostData( $goalInfo['article_url']  ) )
            {
                //$this->setViewContent(  '' . self::__( '<div class="badnews">Project not found</div>' ) . '', true  );
                //return false;
            }
        }
        elseif( $this->getParameter( 'project_name' ) )
        {
            $project =  $this->getParameter( 'project_name' );
            $where =  array( 'article_url' => $project );
            if( ! $goals = ProjectManager_Goals::getInstance()->select( 'goals_id', $where ) )
            {

            }    
			$this->_dbWhereClause['goals_id'] = $goals;
        }

        if( ! self::hasPriviledge( 98 ) && ! ProjectManager::isCustomer( $postData['customer_email'] ) )
        {
            $this->_dbWhereClause['email_address'] = strtolower( Ayoola_Application::getUserInfo( 'email' ) );
        }

        if( empty( $_GET['all_tasks'] ) )
        {
            $this->_dbWhereClause['completion_time'] = '';
        }
		require_once 'Ayoola/Paginator.php';
        $list = new Ayoola_Paginator();
        $this->_sortColumn = 'time';
		$list->pageName = $this->getObjectName();
        $list->listTitle = self::getObjectTitle();
        if( $goalInfo  )
        {
            $list->listTitle = 'Tasks for "' . $goalInfo['goal'] . '"';
        }
        if( $goalInfo &&  $postData )
        {
            $list->listTitle = 'Tasks for "' . $goalInfo['goal'] . '" on "' . $postData['article_title'] . '"';
        }
        $list->hideCheckbox = true;
		$list->noHeader = true;

		$list->setData( $this->getDbData() );
        if( ! $this->getParameter( 'no_list_options' ) )
        {
            $list->setListOptions( 
                array( 
                        'Creator' => '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_Creator?goals_id=' . @$_GET['goals_id'] . '\', \'' . $this->getObjectName() . '\' );" title="">Add New Task</a>',    
                        '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_List?all_tasks=1&goals_id=' . @$_GET['goals_id'] . '\', \'' . $this->getObjectName() . '\' );" title="">All Tasks</a>',    
                    ) 
            );

        }
        else
        {
            $list->setListOptions( 
                array( 
                        'Creator' => '',    
                    ) 
            );
        }
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No pending tasks to show...' );
		
		$list->createList
		(
			array(
                    'task' => array( 'field' => 'task', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Start Time' => array( 'field' => 'time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    'Duration' => array( 'field' => 'duration', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    array( 'field' => 'duration_time', 'value' =>  '%FIELD%', 'value_representation' => array_flip( self::$_timeTable ) ), 
                    array( 'field' => 'completion_time', 'value' =>  '%FIELD%', 'value_representation' => array( '' => '<a style="font-size:smaller;" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_Editor/?' . $this->getIdColumn() . '=%KEY%&goals_id=' . @$_GET['goals_id'] . '&task_edit_mode=completion\', \'' . $this->getObjectName() . '\' );" title="mark as complete">mark as complete</a>', 'pc_paginator_default' => '<i class="fa fa-check"></i>' ) ), 
                    '' => '%FIELD% <a style="font-size:smaller;" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_Editor/?' . $this->getIdColumn() . '=%KEY%&goals_id=' . @$_GET['goals_id'] . '\', \'' . $this->getObjectName() . '\' );"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_Delete/?' . $this->getIdColumn() . '=%KEY%&goals_id=' . @$_GET['goals_id'] . '\', \'' . $this->getObjectName() . '\' );"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
