/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {     // Define changes to default
//configuration here. For example:     // config.language = 'fr';     //
//config.uiColor = '#AADC6E';

        // Toolbar groups configuration.
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing'},
		{ name: 'forms' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'styles' },
		{ name: 'colors' }
	];
        config.removeButtons = 'Underline,Subscript,Superscript,Flash';

        config.filebrowserUploadUrl = '/admin/file/upload/editor';

        config.contentsCss = '/aesthetics/css/ckeditor.css';
};
