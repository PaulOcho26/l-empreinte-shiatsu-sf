import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "icon"]

    toggle() {
        if (this.inputTarget.type === "password") {
            this.inputTarget.type = "text"
            this.iconTarget.textContent = "🔒" // Ou une icône d'œil
        } else {
            this.inputTarget.type = "password"
            this.iconTarget.textContent = "👁️"
        }
    }
}