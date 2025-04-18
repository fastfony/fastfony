import Quill from 'quill';

const toolbarOptions = [
  [
    { header: '2' },
    { header: '3' },
    { header: '4' },
    { header: '5' },
    { header: '6' },
  ],
  [{ size: [] }],
  [{ align: [] }],
  ['bold', 'italic', 'underline', 'strike', 'blockquote'],
  [{ color: [] }, { background: [] }],
  ['blockquote', 'code-block'],
  [{ script: 'sub' }, { script: 'super' }],
  [
    { list: 'ordered' },
    { list: 'bullet' },
    { list: 'check' },
    { indent: '-1' },
    { indent: '+1' },
  ],
  ['link', 'image', 'video'],
  ['clean'],
];

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.rich-text-editor').forEach(function (textarea) {
    const quillContainer = document.createElement('div');
    quillContainer.style.height = '300px';
    textarea.style.display = 'none';

    textarea.parentNode.insertBefore(quillContainer, textarea);

    const quill = new Quill(quillContainer, {
      theme: 'snow',
      modules: {
        toolbar: toolbarOptions,
      },
    });

    quill.on('text-change', function () {
      textarea.value = quill.root.innerHTML;
    });

    quill.root.innerHTML = textarea.value;
  });
});

export default toolbarOptions;
