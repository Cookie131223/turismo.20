<?php
class Destino {
    private $nome;
    private $pais;
    private $descricao;
    private $preco;
    private $imagem;

    public function __construct($nome, $pais, $descricao, $preco, $imagem) {
        $this->nome = $nome;
        $this->pais = $pais;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->imagem = $imagem;
    }

    public function mostrarCard() {
        $precoFormatado = number_format($this->preco, 2, ',', '.');
        // Adicionando um link condicional para o botão de reserva
        // Se a sessão estiver iniciada e o usuário logado, o botão pode ser para uma página de reserva real
        // Caso contrário, pode ser para a página de login/registro
        $botaoReservaLink = isset($_SESSION['user_id']) ? 'reserva.php?destino=' . urlencode($this->nome) : 'login.html';
        $botaoReservaTexto = isset($_SESSION['user_id']) ? 'Reservar' : 'Faça Login para Reservar';

        return '
        <div class="card-destino">
            <img src="'.$this->imagem.'" alt="'.$this->nome.'">
            <div class="card-content">
                <h3>'.$this->nome.', '.$this->pais.'</h3>
                <p>'.$this->descricao.'</p>
                <div class="preco">R$ '.$precoFormatado.'</div>
                <a href="'.$botaoReservaLink.'" class="btn-reserva">'.$botaoReservaTexto.'</a>
            </div>
        </div>';
    }
}
?>