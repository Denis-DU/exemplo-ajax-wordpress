jQuery(document).ready(function($) {
    $("#exemplo-ajax-get-button").click(function() {
        // Essa var "ajaxurl" já é definida pelo Wordpress.
        // O action aqui tem que casar com a action "wp_ajax_*" do back-end
        jQuery.post(ajaxurl, {"action": "exemplo_ajax_get_action"}, function(response) {
            var json = JSON.parse(response);
            console.log(json);
        });
    });

    $("#exemplo-ajax-post-button").click(function() {
        var post_title = $("#exemplo-ajax-post-input").val();
        jQuery.post(ajaxurl, {"action": "exemplo_ajax_post_action", "post_title": post_title}, function(response) {
            var json = JSON.parse(response);
            console.log(json);
        });
    });

    $("#exemplo-ajax-delete-button").click(function() {
        var post_id = $("#exemplo-ajax-delete-id").val();
        jQuery.post(ajaxurl, {"action": "exemplo_ajax_delete_action", "post_id": post_id}, function(response) {
            var json = JSON.parse(response);
            console.log(json);
        });
    });
});