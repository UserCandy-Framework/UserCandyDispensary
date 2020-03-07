$(document).ready(function(){
  var timer;
  $('#blog_content,#blog_title,#blog_description,#blog_keywords,#blog_category').keyup(function(){

   if(timer) {
    clearTimeout(timer);
   }
   timer = setTimeout(saveData, 1000);

  });

  $('#submit').click(function(){
   saveData();
  });
 });

 // Save data
 function saveData(){

  var id = $('#id').val();
  var blog_title = $('#blog_title').val().trim();
  var blog_content = $('#blog_content').val().trim();
  var blog_category = $('#blog_category').val().trim();
  var blog_description = $('#blog_description').val().trim();
  var blog_keywords = $('#blog_keywords').val().trim();
  var token_blog = $('#token_blog').val();
  var blog_autosave = "blog_autosave";

  if(blog_title != '' || blog_content != ''){
   // AJAX request
   $.ajax({
    url: '/BlogAutoSave',
    type: 'post',
    data: {id:id,blog_title:blog_title,blog_content:blog_content,blog_category:blog_category,blog_description:blog_description,blog_keywords:blog_keywords,token_blog:token_blog,blog_autosave:blog_autosave},
    success: function(data){
      if(data != '')
      {
        $('#id').val(data);
      }
      $('#autoSave').html("<b><font color=green>Saved as draft...</font></b>");
      console.log("Save Data as Draft");
      setInterval(function(){
        $('#autoSave').text('');
      }, 5000);
    }
   });
  }
 }
