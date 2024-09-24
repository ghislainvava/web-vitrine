// assets/controllers/animate_card_controller.js
import { Controller } from '@hotwired/stimulus';

export default class AnimateCardController extends Controller {
    connect() {
        console.log("AnimateCardController connected"); // Vérifiez si le contrôleur se connecte
        this.initObserver();
        this.element.classList.add('hidden'); // Cache l'élément au départ
        console.log("Element hidden initially: ", this.element);
    }

    initObserver() {
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        // Création d'un nouvel IntersectionObserver
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    console.log("Card is intersecting: ", entry.target);
                    this.triggerAnimation(entry.target);
                } else {
                    console.log("Card is not intersecting: ", entry.target);
                }
            });
        }, options);

        // Observation de l'élément du contrôleur
        this.observer.observe(this.element);
    }

    triggerAnimation(element) {
        const animationClass = 'animate__fadeInDown'; 
        const delay = element.dataset.delay || '0s';

        // Retirer la classe hidden pour rendre l'élément visible
        element.classList.remove('hidden');
        console.log("Removing hidden class from: ", element);

        // Appliquer l'animation
        element.classList.add('animate__animated', animationClass);
        element.style.setProperty('animation-delay', delay);

        // Arrêter l'observation après le déclenchement de l'animation
        this.observer.unobserve(element);
    }
}