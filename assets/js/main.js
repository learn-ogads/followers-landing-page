document.addEventListener("alpine:init", () => {
    Alpine.store("form", {
        loading: false,
        errors: [],
        page: 0,
        data: {
            username: "",
            platform: "instagram",
            amount: 250
        },
        offers: [],

        getFollowersPage() {
            this.page = 1
        },
        async getOffersPage() {
            this.page = 2
            await this.setSession()
            await this.fetchOffers()
        },

        setPlatform(platform) {
            this.data.platform = platform
        },

        setFollowerAmount(amount) {
            this.data.amount = amount
        },

        calculateConversionsRequired() {
            switch(this.data.amount) {
                case 250:
                    return 1;
                case 500:
                    return 2;
                default:
                    return 3;
            }
        },

        async setSession() {
            this.loading = true
            this.errors = []
            const resp = await fetch(`./api/session.php?followers=${this.data.amount}&username=${this.data.username}&platform=${this.data.platform}`)
            const json = await resp.json()
            if (!json.success) {
                this.errors.push(json.error)
                return
            }
            this.loading = false
        },

        async fetchOffers() {
            this.loading = true
            this.errors = []
            const resp = await fetch("./api/offers.php")
            const json = await resp.json()
            if (!json.success) {
                this.errors.push(json.error)
                return
            }
            this.offers = json.data
            this.loading = false
        },

        openUrl(offer) {
            let url = `./api/click.php?offer_id=${offer.offerid}&followers=${this.data.amount}&link=${encodeURIComponent(offer.link)}`
            window.open(url, "_blank").focus()
        }

    })
});
document.addEventListener("DOMContentLoaded", () => {
    const recheckTime = 60000

    async function isComplete() {
        const resp = await fetch("./api/status.php")
        const json = await resp.json()
        if (json.success) {
            window.location.replace("./pages/completed.php")
            return
        }

        setTimeout(isComplete, recheckTime)
    }

    isComplete()
});