// Carousel Auto-Cycle
// $(document).ready(function() {
    // $('#carouselExampleControls').carousel();

    // $('.carousel-inner .item').each(function() {
    //     var itemToClone = $(this);
    //     console.log(itemToClone);

    //     for (var i=1;i<6;i++) {
    //             itemToClone = itemToClone.next();

    //             if (!itemToClone.length) {
    //             itemToClone = $(this).siblings(':first');
    //         }

    //         itemToClone.children(':first-child').clone()
    //         .addClass("cloneditem-"+(i))
    //         .appendTo($(this));
    //     }
    // });

//     let items = document.querySelectorAll('.carousel .carousel-item');

//     items.forEach((el) => {
//         const minPerSlide = 4
//         let next = el.nextElementSibling
//         for (var i=1; i<minPerSlide; i++) {
//             if (!next) {
//                 // wrap carousel by using first child
//                 next = items[0]
//             }
//             let cloneChild = next.cloneNode(true)
//             el.appendChild(cloneChild.children[0])
//             next = next.nextElementSibling
//         }
//     })
// });
