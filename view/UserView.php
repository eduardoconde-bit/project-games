<?php
class UserView {
    public static function menuUserView() {
        
    }

    public static function msgFailedUpdate() {
        $html = <<<HTML
            <p class="msg">Erro ao atualizar informações, tente novamente!</p>
        HTML;
        echo $html;
    }

    public static function msgNameExists() {
        $html = <<<HTML
            <p class="msg">Insira um nome de usuário difente!</p>
        HTML;
        echo $html;
    }
}