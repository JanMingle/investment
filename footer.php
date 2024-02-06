<!-- footer.php -->

<style>
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        padding: 10px;
        text-align: center;
    }

    .footer p {
        color: gold;
        margin: 0; /* Remove default margin to avoid extra space */
        animation: moveText 3s linear infinite alternate; /* Add animation to move the text */
    }

    @keyframes moveText {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(20px);
        }
    }
</style>

<div class="footer">
    <p>Created by J Mingle in 2024</p>
</div>
