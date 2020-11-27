<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    ProjectManager_Goals_Abstract
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Monday 16th of December 2019 09:10AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class ProjectManager_Goals_Duplicate extends ProjectManager_Goals_Creator
{

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {
        try {
            //  Code that runs the widget goes here...
            $this->createForm('Submit...', 'Duplicate a project goal');
            $this->setViewContent($this->getForm()->view());

            //	self::v( $_POST );
            if (! $values = $this->getForm()->getValues()) {
                return false;
            }

            $goal = ProjectManager_Goals::getInstance()->selectOne( null, array( 'goals_id' => $values['preset'] ) );   
            $tasks = ProjectManager_Tasks::getInstance()->select( null, array( 'goals_id' => $values['preset'] ) );   
            //    var_export( $tasks );
            $goalInfo = $goal;
            unset( $goalInfo['goals_id'] );
            $class = new ProjectManager_Goals_Creator( array( 'fake_values' => $goalInfo ) );
            $class->initOnce();
            $this->setViewContent( $class->view(), true  ); 
            if( $goalId = $class->insertInfo )
            {
                foreach( $tasks as $task )
                {
                    $taskInfo = $task;
                    unset( $taskInfo['tasks_id'] );
                    $taskInfo['goals_id'] = $goalId['goals_id'];
                    $taskInfo['time'] = time();
                    //    var_export( $taskInfo );
                    $class = new ProjectManager_Tasks_Creator( array( 'fake_values' => $taskInfo ) );
                    $class->initOnce();
                    //    var_export( $class->getForm()->getValues() );
                    //    var_export( $class->getForm()->getBadnews() );
                    $this->setViewContent( $class->view()  ); 

                }
            }

            // end of widget process
        } catch (Exception $e) {
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(self::__('<p class="badnews">' . $e->getMessage() . '</p>'));
            $this->setViewContent(self::__('<p class="badnews">Theres an error in the code</p>'));
            return false;
        }
    }

    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;

		$fieldset = new Ayoola_Form_Element;

        $options = ProjectManager_Goals_Abstract::getGoals();


        $fieldset->addElement( array( 'name' => 'preset', 'label' => 'Duplicate Exist Project Goal', 'type' => 'Select', 'value' => @$values['preset'] ), $options );
		if( empty( $_GET['article_url'] ) )
		{
			$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'InputText', 'value' => @$values['article_url'] ) ); 
		}

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
