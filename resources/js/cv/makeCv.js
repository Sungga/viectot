// ------------------------------------------<< js show menu user >>------------------------------------
const userBtn = document.querySelector('.header__item--account');
const menuUser = document.querySelector('.header__menu--user');

// Toggle hiển thị khi click vào nút user
userBtn.addEventListener('click', (event) => {
    event.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
    menuUser.style.visibility = menuUser.style.visibility === 'visible' ? 'hidden' : 'visible';
    menuUser.style.opacity = menuUser.style.visibility === 'hidden' ? '0' : '1';
    menuUser.style.top = menuUser.style.visibility === 'hidden' ? '0' : '100%';
});

// Ẩn menu khi click ra ngoài
document.addEventListener('click', (event) => {
    if (!menuUser.contains(event.target) && !userBtn.contains(event.target)) {
        menuUser.style.visibility = 'hidden';
        menuUser.style.opacity = '0';
        menuUser.style.top = '0';
    }
});

// ----------------------------------------<< js main for make cv >>------------------------------
// const editor = grapesjs.init({
//     container: '#gjs',
//     fromElement: true,
//     height: '100%',
//     width: 'auto',
//     storageManager: false,
//     panels: {
//         defaults: [
//         {
//             id: 'panel-top',
//             el: '.panel__top',
//             buttons: [{
//             id: 'save',
//             className: 'fa fa-floppy-o',
//             label: 'Save',
//             command: 'save-command',
//             }]
//         },
//         {
//             id: 'panel-left',
//             el: '#blocks', // Thêm block vào Sidebar
//             resizable: true,
//         },
//         ],
//     },
//     blockManager: {
//         appendTo: '#blocks', // Đây là phần Sidebar chứa các block
//     },
//     });

// // Khối "2 Cột"
// editor.BlockManager.add('2-columns', {
// label: '2 Cột',
// content: `<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
//             <div style="background: #f2f2f2; padding: 20px;">Cột 1</div>
//             <div style="background: #e2e2e2; padding: 20px;">Cột 2</div>
//             </div>`,
// category: 'Layout',
// });

// // Khối "3 Cột"
// editor.BlockManager.add('3-columns', {
// label: '3 Cột',
// content: `<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
//             <div style="background: #f2f2f2; padding: 20px;">Cột 1</div>
//             <div style="background: #e2e2e2; padding: 20px;">Cột 2</div>
//             <div style="background: #d2d2d2; padding: 20px;">Cột 3</div>
//             </div>`,
// category: 'Layout',
// });

// // Khối "4 Cột"
// editor.BlockManager.add('4-columns', {
// label: '4 Cột',
// content: `<div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
//             <div style="background: #f2f2f2; padding: 20px;">Cột 1</div>
//             <div style="background: #e2e2e2; padding: 20px;">Cột 2</div>
//             <div style="background: #d2d2d2; padding: 20px;">Cột 3</div>
//             <div style="background: #c2c2c2; padding: 20px;">Cột 4</div>
//             </div>`,
// category: 'Layout',
// });

// // Khối "Flexbox - Hàng ngang"
// editor.BlockManager.add('flex-row', {
// label: 'Hàng ngang',
// content: `<div style="display: flex; gap: 10px;">
//             <div style="flex: 1; padding: 10px; background: #eee;">Cột 1</div>
//             </div>`,
// category: 'Layout',
// });

// editor.BlockManager.add('a4-page', {
//     label: 'Trang A4',
//     category: 'Layout',
//     content: `<div class="page">
//                 <h2>Tiêu đề CV</h2>
//                 <p>Thông tin cá nhân...</p>
//                 </div>`,
// });
    
// editor.DomComponents.addType('resizable-box', {
// model: {
//     defaults: {
//     resizable: {
//         tl: 0, // top-left
//         tc: 0, // top-center
//         tr: 0, // top-right
//         cl: 0, // center-left
//         cr: 1, // center-right
//         bl: 0, // bottom-left
//         bc: 0, // bottom-center
//         br: 1, // bottom-right
//     },
//     draggable: true,
//     droppable: true,
//     }
// }
// });


const editor = grapesjs.init({
    container: '#gjs',
    fromElement: true,
    height: '100%',
    width: 'auto',
    storageManager: false,
    panels: {
      defaults: [
        {
          id: 'panel-top',
          el: '.panel__top',
          buttons: [{
            id: 'save',
            className: 'fa fa-floppy-o',
            label: 'Save',
            command: 'save-command',
          }]
        },
        {
          id: 'panel-left',
          el: '#blocks',
          resizable: true,
        },
      ],
    },
    blockManager: {
      appendTo: '#blocks',
    },
  });
  
  // Tạo tooltip hiển thị kích thước
  const sizeInfo = document.createElement('div');
  sizeInfo.style.position = 'absolute';
  sizeInfo.style.background = 'rgba(0, 0, 0, 0.75)';
  sizeInfo.style.color = '#fff';
  sizeInfo.style.padding = '3px 8px';
  sizeInfo.style.fontSize = '12px';
  sizeInfo.style.borderRadius = '5px';
  sizeInfo.style.zIndex = '9999';
  sizeInfo.style.pointerEvents = 'none';
  sizeInfo.style.display = 'none';
  sizeInfo.style.fontFamily = 'Arial, sans-serif';
  document.body.appendChild(sizeInfo);
  
  function updateTooltipPosition() {
    const selected = editor.getSelected();
    if (selected && selected.getEl()) {
      const el = selected.getEl();
      const rect = el.getBoundingClientRect();
      sizeInfo.innerText = `${Math.round(rect.width)} x ${Math.round(rect.height)} px`;
      sizeInfo.style.display = 'block';
      sizeInfo.style.left = `${rect.left + window.scrollX + rect.width / 2 - sizeInfo.offsetWidth / 2}px`;
      sizeInfo.style.top = `${rect.top + window.scrollY - sizeInfo.offsetHeight - 8}px`;
    }
  }
  
  let resizeInterval;
  
  editor.on('component:selected', () => {
    updateTooltipPosition();
    clearInterval(resizeInterval);
    resizeInterval = setInterval(() => {
      updateTooltipPosition();
    }, 100);
  });
  
  editor.on('component:deselected', () => {
    clearInterval(resizeInterval);
    sizeInfo.style.display = 'none';
  });
  
  window.addEventListener('scroll', updateTooltipPosition);
  window.addEventListener('resize', updateTooltipPosition);
  editor.on('canvas:scroll', updateTooltipPosition);
  
  editor.on('load', () => {
    const canvasBody = editor.Canvas.getBody();
    const observer = new MutationObserver(() => {
      updateTooltipPosition();
    });
    observer.observe(canvasBody, {
      attributes: true,
      subtree: true,
      attributeFilter: ['style']
    });
  });
  
  editor.DomComponents.addType('resizable-box', {
    model: {
      defaults: {
        resizable: {
          tl: 0,
          tc: 0,
          tr: 0,
          cl: 1,
          cr: 1,
          bl: 0,
          bc: 1,
          br: 1,
        },
        draggable: true,
        droppable: true,
      }
    }
  });
  
  editor.BlockManager.add('2-columns', {
    label: '2 Cột',
    content: `
      <div style="display: flex; gap: 10px;">
        <div style="background: #f2f2f2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 1</div>
        <div style="background: #e2e2e2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 2</div>
      </div>`,
    category: 'Layout',
  });
  
  editor.BlockManager.add('3-columns', {
    label: '3 Cột',
    content: `
      <div style="display: flex; gap: 10px;">
        <div style="background: #f2f2f2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 1</div>
        <div style="background: #e2e2e2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 2</div>
        <div style="background: #d2d2d2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 3</div>
      </div>`,
    category: 'Layout',
  });
  
  editor.BlockManager.add('4-columns', {
    label: '4 Cột',
    content: `
      <div style="display: flex; gap: 10px;">
        <div style="background: #f2f2f2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 1</div>
        <div style="background: #e2e2e2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 2</div>
        <div style="background: #d2d2d2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 3</div>
        <div style="background: #c2c2c2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 4</div>
      </div>`,
    category: 'Layout',
  });
  
  editor.BlockManager.add('flex-row', {
    label: 'Hàng ngang',
    content: `
      <div style="display: flex; gap: 10px;">
        <div style="background: #f2f2f2; padding: 20px; width: 100px; height: 100px;" data-gjs-type="resizable-box">Cột 1</div>
      </div>`,
    category: 'Layout',
  });
  
  editor.BlockManager.add('a4-page', {
    label: 'Trang A4',
    category: 'Layout',
    content: `<div class="page" style="width: 210mm; height: 297mm; padding: 20mm; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); margin: 10px auto;">
      <h2>Tiêu đề CV</h2>
      <p>Thông tin cá nhân...</p>
    </div>`,
  });