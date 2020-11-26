<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Table_Sample
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php Friday 26th of October 2018 02:52PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class ProjectManager_Settings extends PageCarton_Settings
{


	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		$settings = unserialize( @$values['settings'] ) ? : $values['settings'];
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;



        //  Sample Text Field Retrieving E-mail Address
		$fieldset->addElement( array( 'name' => 'post_type', 'placeholder' => 'e.g. project-manager', 'label' => 'Post Type for Projects', 'value' => @$settings['post_type'], 'type' => 'InputText' ) );

/* 
        //  Check box
		$options = array( 
							'option_value1' => 'Option 1', 
							'option_value2' => 'Option 2', 
							);
		$fieldset->addElement( array( 'name' => 'other_options', 'label' => 'Other Options', 'value' => @$settings['other_options'], 'type' => 'Checkbox' ), $options );
 */		
		$fieldset->addLegend( 'Project Manager Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
