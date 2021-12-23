<?php
/**
 * Plugin Name: Exemplo AJAX Wordpress
 * Description: Apenas um exemplo de requisição ajax
 * Version: 1.0.0
 * Author: FCL
 */

add_action("admin_menu", function() {
    // Adicionando página no submenu
    $submenu = add_submenu_page("edit.php", "Exemplo AJAX", "Exemplo AJAX", "edit_posts", "exemplo_ajax", function() {
        echo <<<EOT
            <h1> Exemplo AJAX </h1>
            <p> Abrir o Console do navegador para ver os resultados </p>
            <ul>
                <li>
                    <h2> Listar </h2>
                    <button id="exemplo-ajax-get-button">Listar posts</button>
                </li>
                <li>
                    <h2> Cadastrar </h2>
                    Nome do post: <input type='text' id='exemplo-ajax-post-input' />
                    <button id="exemplo-ajax-post-button">Cadastrar post</button>
                </li>
                <li>
                    <h2> Apagar </h2>
                    ID do Post: <input type='text' id='exemplo-ajax-delete-id' />
                    <button id="exemplo-ajax-delete-button">Apagar posts</button>
                </li>
            </ul>
EOT;
        }
    );

    // Esta action aqui só é chamada quando carrego especificamente a página criada acima. Isso é bom porque não afeta as demais páginas
    add_action("load-$submenu", function() { 
        add_action("admin_enqueue_scripts", function(){ 
            wp_enqueue_script("exemplo-ajax", WP_PLUGIN_URL . "/exemplo-ajax/exemplo-ajax.js", ["jquery"]);
        });
    });
});

// Action para listar posts. Note como o sufixo "exemplo_ajax_get_action" casa com o parâmetro "action" que estou passando no exemplo-ajax.js
add_action("wp_ajax_exemplo_ajax_get_action", function() {
    $posts = get_posts(["numberposts" => -1]);
    echo json_encode($posts);
    wp_die();
});

add_action("wp_ajax_exemplo_ajax_post_action", function() {
    if (! current_user_can("publish_posts")) {
        echo json_encode((object) ["erro"=>"Usuário não tem permissões o suficiente"]);
        wp_die();
    }

    $id = wp_insert_post([
        "post_title" => wp_strip_all_tags($_POST["post_title"]),
        "post_status" => "publish",
    ]);
    if ($id !== 0) {
        echo json_encode(get_post($id));
    }
    else {
        echo json_encode((object) ["erro"=>"Ocorreu um erro ao salvar o post"]);
    }
    wp_die();
});

add_action("wp_ajax_exemplo_ajax_delete_action", function() {
    if (! current_user_can("delete_posts")) {
        echo json_encode((object) ["erro"=>"Usuário não tem permissões o suficiente"]);
        wp_die();
    }

    $post_id = (int) $_POST["post_id"];
    $post_data = wp_delete_post($post_id);
    if ($post_data) {
        echo json_encode($post_data);
    }
    else {
        echo json_encode((object) ["erro"=>"Ocorreu um erro ao apagar o post"]);
    }
    wp_die();
});