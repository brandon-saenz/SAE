tinymce.init({
	selector: '#kt-tinymce-2',
    language: 'es_MX',
    menubar: false,
    toolbar: [
        'styleselect fontselect fontsizeselect',
        'bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | preview'
    ], 
    plugins : 'advlist autolink link image lists charmap print preview code',
    statusbar: false
});

KTTinymce.init();