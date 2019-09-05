let newtoolbarOptions = [
    ['bold', 'italic'],
    ['blockquote','code-block'],
    [{
      'list': 'ordered'
    }, {
      'list': 'bullet'
    }],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['link', 'image'],
    ['clean']
  ];

  let newquill = new Quill('#editPostEditor', {
    theme: 'snow',
    modules: {
      toolbar:newtoolbarOptions
    },
    placeholder: 'Compose an epic...'
  });
  j(".ql-toolbar").css("display", "block");

  j('#tag').on('tokenfield:createdtoken', function(e) {
    // .. do stuff here
    j(".token").addClass("standardColor");
  })
  .tokenfield({
      autocomplete:{
        source:[
          'Politics',
          'Sports',
          'Health',
          'Technology',
          'Music',
          'News-Lifestyle',
          'Movies',
          'Fitness'
        ],
        delay:100,

      },
      showAutocompleteOnFocus: true,
      createTokensOnBlur: true,
    });


function editPost(post_id){

    j.ajax({
        type:"GET",
        url:"post-data/"+post_id,
        success:function (data) {
         j('#post-title').val(data.data.title)
         const editor = document.getElementsByClassName('ql-editor');
         editor[1].innerHTML = data.data.body
         if(data.data.tags !=null) {
          j('#tag').tokenfield('setTokens',data.data.tags)
         }else{
          j('#tag').tokenfield('setTokens',[])
         }
          
          j('#post_id').val(data.data.id)
        },
        error:function (error){
         console.log(error)
        }
      })
}


const editForm = document.querySelector('.edit-post-form');
const saveBtn = document.querySelector('input[name="savePost"]');
if(saveBtn !=null){
  editForm.onsubmit = saveBtn.addEventListener('click',function(e){
    e.preventDefault();
    const editFormData = new FormData(document.querySelector('.edit-post-form'));
    const postEditor =document.getElementsByClassName('ql-editor');
    const post = postEditor[1].innerHTML;
    const postTitle = document.querySelector("#post-title").value;

    const editTurndownService = new TurndownService({
      codeBlockStyle: 'fenced'
    });

    const gfm = turndownPluginGfm.gfm;
    editTurndownService.use(gfm);

    let editMarkdown = editTurndownService.turndown(post);
    if (editMarkdown !== "" && postTitle !== "") {
      editFormData.set('title', postTitle);
      // check if the form is being submitted
      // which would mean a new post is being created rather than saving a draft
      const newPostIsBeingCreated = event.target instanceof HTMLFormElement;

      // get all editImageURIs in the document
      let editImageURIs = editMarkdown.match(/\!\[\]\(data:image\/\w+;base64,[^)]*\)/g);
      // are there images in the blog post?
      if (editImageURIs) {
        // remove duplicates
        editImageURIs = editImageURIs.reduce((acc, curVal) => {
          if (!acc.includes(curVal)) acc.push(curVal);
          return acc;
        }, []);



        editImageURIs.forEach(imageURI => {
          const [, fullURI, ext, uriData] = imageURI.match(/\!\[\]\((data:image\/(\w+);base64,([^)]*))\)/);
          const id = Math.random().toString(36).substr(2, 10);
          const newImgName = `img-${id}.${ext}`;
          const username = j('meta[name="user_id"]').attr('content');




          // replace the image URI everywhere it occurs in the editMarkdown
          let stillMatching = true;
          while (stillMatching) {
            if (editMarkdown.includes(fullURI)) {
              editMarkdown = editMarkdown.replace(fullURI, `/storage/${username}/images/${newImgName}`);
            } else {
              stillMatching = false;
            }
          }

          // add this info to the form data being sent to the backend
          editFormData.set(newImgName, uriData);
        });

      }


      editFormData.set('postVal', editMarkdown);

    j.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': j('meta[name="csrf-token"]').attr('content')
        }
    });

    j.ajax({
          type: "POST",
          dataType:'json',
          url : "edit-post",
          data:editFormData,
          contentType: false,
          processData: false,
          beforeSend:function(){
            j('.savePostBtn').text('Saving...');
          },
          success : function (res) {
            // console.log(JSON.stringify(res));
            j('.savePostBtn').text('Saved');
              if (res.error == false && res.action == 'update') {
                window.localStorage.setItem('update', 'success');
                window.location = '/'+j('meta[name="username"]').attr('content')+'/posts';

              } 
          },
          error:function (error){
            j('.savePostBtn').text('Save');
            console.log(error.statusText);
          }
      });

    }else {
      swal({
        text: "Sorry,both fields are required!",
        icon: "error",
        button: {
          text: "OK",
          value: true,
          visible: true,
          className: "standard-color",
          closeModal: true,
      },
      });
    }

  })
}

j(document).ready(function() {
  const updated = window.localStorage.getItem('update');
 
  if (updated == 'success') {
    window.localStorage.removeItem('update');
    swal({
      text: "Your post was successfully updated!",
      icon: "success",
      button: {
        text: "OK",
        value: true,
        visible: true,
        className: "standard-color",
        closeModal: true,
    },
    });
  }
})
