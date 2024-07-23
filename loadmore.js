class LoadMore {
    constructor() {
        this.ajaxUrl = loadMoreRequest?.ajaxUrl ?? '';
        this.ajaxNonce = loadMoreRequest?.ajaxNonce ?? '';
        this.loadMoreButton = jQuery('#load-more');

        this.init();
    }

    init() {
        if (!this.loadMoreButton.length) {
            return;
        }

        this.loadMoreButton.on('click', () => this.handleLoadMorePosts());
    }

    handleLoadMorePosts() {
        const page = this.loadMoreButton.data('page');
        if(!page) {
            return null;
        }

        const newPage = parseInt(page) + 1;

        jQuery.ajax({
            type: "post",
            url: this.ajaxUrl,
            data: {
                page: page,
                action: 'load_more',
                ajax_nonce: this.ajaxNonce
            },
            success: function (response) {
                if (0 === parseInt(response)) {
                    this.loadMoreButton.remove();
                } else {
                    this.loadMoreButton.data('page', newPage);
                    jQuery('load-more-content').append(response);
                }
            },
            
        });
    }
}

jQuery(document).ready(function () {
    new LoadMore();
});