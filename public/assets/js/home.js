class Carousel{
    /**
     * @callback moveCallback
     * @param {number} index
     */

    /**
     *  @param {HTMLElement} element
     *  @param {object} options
     *  @param {object} [options.slidesToScroll=1] Nombre d'éléments a faire défiler
     *  @param {object} [options.slidesVisible=1] Nombre d'éléments visible dans un slide
     *  @param {boolean} [options.loop=false] Doit-t-on boucler en fin de carousel
     *  @param {boolean} [options.infinite=false]
     *  @param {boolean} [options.pagination=false]
     *  @param {boolean} [options.navigation=true]
     * */
    constructor(element, options = {}) {

        this.element = element
        this.options = Object.assign({},{
            slidesToScroll: 1,
            slidesVisible: 1,
            loop: false,
            pagination: false,
            navigation: true,
            infinite: false
        }, options)
        let children = [].slice.call(element.children)
        this.index = 0
        this.currentItem = 0
        this.isMobile = false
        this.offset = 0

        this.pagination = 0
        this.buttons = []
        this.root = this.createDivWithClass('carousel')
        this.container = this.createDivWithClass('carousel__container')
       // this.root.setAttribute("tabindex", "0")
        this.root.appendChild(this.container)
        this.element.appendChild(this.root)
        this.moveCallbacks = []
        this.items = children.map((child) => {
            let item = this.createDivWithClass('carousel__item')
            item.appendChild(child)
            return item
        })

        // Test
        this.itemsOld = this.items
        if (this.itemsOld.length >= 6)
        {
            this.options.slidesVisible = 6
            this.options.slidesToScroll = 6
        }
        else
        {
            this.options.slidesVisible = this.itemsOld.length
            this.options.slidesToScroll = this.itemsOld.length
        }

        this.start = this.slidesVisible % 2
        if (this.slidesVisible == 1)
            this.start = 0

        this.maxPagination = Math.ceil((this.itemsOld.length / this.slidesToScroll))
        if (this.options.infinite) {
            this.offset = this.options.slidesVisible * 2 - 1
            this.index = this.offset
            if (this.offset > this.itemsOld.length || this.offset == 1)
                this.offset = this.itemsOld.length
            this.items = [
                ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
                ...this.items,
                ...this.items.slice(0, this.offset).map(item => item.cloneNode(true)),
            ]
            this.gotoItem(this.offset, false)
        }
        this.items.forEach(item => this.container.appendChild(item))


        this.setStyle()
        if (this.options.navigation)
            this.createNavigation()

        // Events
        this.moveCallbacks.forEach(cb => cb(this.currentItem))
        window.addEventListener('resize', this.onWindowResize.bind(this))
        this.onWindowResize()
        this.createPagination()
        if (!this.isMobile) {
            let activeButton = this.buttons[this.pagination]
            if (activeButton) {
                this.buttons.forEach(button => button.classList.remove('carousel__pagination__button--active'))
                activeButton.classList.add('carousel__pagination__button--active')
            }
        }
        else {
            let activeButton = this.buttons[this.pagination]
            if (activeButton) {
                this.buttons.forEach(button => button.classList.remove('carousel__pagination__IsMobile__button--active'))
                activeButton.classList.add('carousel__pagination__IsMobile__button--active')
            }
        }

        this.root.addEventListener('keyup', e => {

            if (e.key === 'ArrowRight' || e.key === 'Right') {
                this.next();
            }
            else if (e.key === 'ArrowLeft' || e.key === 'Left') {
                this.prev();
            }
        });

        let listenerOfMoreInformation = document.querySelectorAll('[id^=c_]')
        listenerOfMoreInformation.forEach((item) => {
            item.addEventListener('click', () => {
                let res = item.parentElement.getElementsByClassName("item__description")
                res[0].style.display = "block"
                //res[0].css("display", "block")
                //child.getElementsByClassName("item__description").style("display:block")
            })
        })


    }

    /**
     * Applique les bonnes dimensions aux éléments du carousel
     */
    setStyle(){
        let ratio = this.items.length / this.slidesVisible
        this.container.style.width = (ratio * 100) + "%"
        this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%")
    }

    /**
     * Crée les fleches de navigation
     */
    createNavigation(){
        let nextButton = this.createDivWithClass('carousel__next');
        let prevButton = this.createDivWithClass('carousel__prev');
        this.root.appendChild(nextButton)
        this.root.appendChild(prevButton)
        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))
        if (this.options.loop === true) {
            return ;
        }
        this.onMove(index => {
            if (index === 0) {
                prevButton.classList.add('carousel__prev--hidden')
            }else{
                prevButton.classList.remove('carousel__prev--hidden')
            }
            if (this.items[this.currentItem + this.slidesVisible] === undefined){
                nextButton.classList.add('carousel__next--hidden')
            }else{
                nextButton.classList.remove('carousel__next--hidden')
            }
        })
    }

    /**
     * Remove button pagination where changing the resolution
     * @param {String} classname
     */
    removeButton(classname) {
        let buttonsOld;
        if ((buttonsOld = document.querySelectorAll(classname)))
        {
            buttonsOld.forEach(element => {
                element.remove()
            });
        }
    }

    /**
     * Crée la pagination dans le DOM
     */
    createPagination(){
        let pagination = this.createDivWithClass('carousel__pagination')

        let count = this.items.length - 2 * this.offset
        this.root.appendChild(pagination)
        if (this.isMobile)
        {
            this.removeButton(".carousel__pagination__button")

            for (let i = 0; i < ((this.items.length - 2 * this.offset) - this.start); i = i + this.slidesToScroll)
            {
                let button = this.createDivWithClass('carousel__pagination__IsMobile__button')
                this.index = i + this.offset
                button.addEventListener('click', () => {
                    this.index = i + this.offset
                    this.pagination =  this.index -  this.itemsOld.length
                    this.gotoItem(i + this.offset)
                })
                pagination.appendChild(button)
                this.buttons.push(button);
            }
           this.onMove(index => {
//                let activeButton = buttons[Math.floor(((index - this.offset)  %count) / this.slidesToScroll)]
                let activeButton = this.buttons[this.pagination]
                if (activeButton) {
                    this.buttons.forEach(button => button.classList.remove('carousel__pagination__IsMobile__button--active'))
                    activeButton.classList.add('carousel__pagination__IsMobile__button--active')
                }
           })
        }
        else {
            this.removeButton(".carousel__pagination__IsMobile__button")
            for (let i = 0; i < ((this.items.length - 2 * this.offset) - this.start); i = i + this.slidesToScroll)
            {
                let button = this.createDivWithClass('carousel__pagination__button')
                button.addEventListener('click', () => {
                    this.index = i + this.offset
                    this.pagination =  this.index -  this.itemsOld.length
                    this.gotoItem(i + this.offset)
                })
                pagination.appendChild(button)
                this.buttons.push(button);
            }
            this.onMove(index => {
                // let activeButton = buttons[Math.floor(((index - this.offset) % count ) / this.slidesToScroll)]
                let activeButton = this.buttons[this.pagination]
                if (activeButton) {
                    this.buttons.forEach(button => button.classList.remove('carousel__pagination__button--active'))
                    activeButton.classList.add('carousel__pagination__button--active')
                }
            })
        }
    }

    next(){
        this.pagination++

        if (this.pagination >= this.maxPagination){
            this.pagination = 0
        }
        this.index = this.currentItem + this.slidesToScroll
        this.gotoItem(this.currentItem + this.slidesToScroll)
        //test pas event of transition3d

        if ((this.currentItem - this.offset + this.slidesVisible) < 0 || (this.currentItem - this.offset) >= this.itemsOld.length)
        {
            this.resetInfinite(this.index)
        }
    }

    prev(){
        this.pagination--
        if (this.pagination < 0){
            this.pagination = this.maxPagination - 1
        }
        this.index = this.currentItem - this.slidesToScroll
        this.gotoItem(this.currentItem - this.slidesToScroll)

//test pas event of transition3d
        if ((this.currentItem - this.offset + this.slidesVisible) < 0 || (this.currentItem - this.offset) >= this.itemsOld.length)
        {
            this.resetInfinite(this.index)
        }




    }

    resetInfinite(index) {
        if (this.index <= 0) {
            this.index = this.currentItem +  2 * this.offset
            if (this.options.slidesVisible == 1)
                this.index = this.offset
            this.gotoItem(this.index + (this.items.length - 2 * this.offset - 1), false)
        }
        else if ((index - this.offset) > this.itemsOld.length ||
            (this.options.slidesVisible == 1 && this.index > this.itemsOld.length))
        {
            this.index = this.index - (this.items.length - 2 * this.offset)
            if (this.options.slidesVisible == 1)
                this.index = this.offset
            this.gotoItem(this.index - (this.items.length - 2 * this.offset - 1), false)

        }
    }

    /**
     * Déplace le carousel vers l'élément ciblé
     * @param {number} index
     * @param {boolean} [animation=true]
     */
    gotoItem(index, animation=true) {
        if (this.index < 0 && this.options.loop){

            if (this.options.loop) {
                this.index = this.items.length - this.slidesVisible
            }
            else
                return

        } else if (this.options.loop &&(this.index >= this.items.length ||
            (this.items[this.currentItem + this.slidesVisible] === undefined &&
                this.index > this.currentItem))) {

            if (this.options.loop){
                this.index = 0
            }
            else
                return
        }
        if (animation === false) {
            this.container.style.transition = 'none'
        }
        let translateX = this.index * -100 / this.items.length
        this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)'
        this.container.offsetHeight // force repaint
        if (this.options.animation === false) {
            this.container.style.transition = ''
        }

        this.currentItem = this.index

        this.moveCallbacks.forEach(cb => cb(this.index))
    }

    /**
     *  @param {moveCallback} cb
     */
    onMove(cb){
        this.moveCallbacks.push(cb)
    }

    onWindowResize() {
        let mobile = window.innerWidth < 800
        if (mobile !== this.isMobile) {
            this.isMobile = mobile

        }
        this.setStyle()
        this.moveCallbacks.forEach(cb => cb(this.currentItem))
        this.createPagination()
    }

    /**
     *
     * @param {string} className
     * @returns {HTMLElement}
     * */
    createDivWithClass(className) {
        let div = document.createElement('div')
        div.setAttribute('class',className)
        return div
    }

    /**
     * @return {number}
     */
    get slidesToScroll() {
        return this.isMobile ? 1 : this.options.slidesToScroll
    }

    /**
     * @return {number}
     */
    get slidesVisible() {
        return this.isMobile ? 1 : this.options.slidesVisible
    }

}

let onReady = function() {


    let test = document.querySelectorAll('[id^=carousel_]');
    test.forEach(item => {
        let id = item.getAttribute("id")
        new Carousel(document.querySelector('#' + id), {
            slidesVisible: 3,
            slidesToScroll: 3,
            loop: false,
            pagination: true,
            infinite: true
        })
    })
}

if (document.readyState !== 'loading') {
    onReady()
}

document.addEventListener('DOMContentLoaded', onReady)

