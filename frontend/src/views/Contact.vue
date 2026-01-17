<template>
  <div class="bg-[#2a2a28] text-[#fffaf0] font-serif min-h-screen flex flex-col">

    <!-- Header -->
    <Header />

    <!-- ================= INTRO CONTACT ================= -->
  <!-- ================= INTRO CONTACT ================= -->
<section class="relative py-24 md:py-32 bg-gradient-to-b from-[#6b4f3a]/10 to-[#faf0e0]">
  <!-- Décor vintage subtil -->
  <div class="absolute inset-0 opacity-10 pointer-events-none">
    <img src="/images/about-hero.jpeg" alt="Vintage Pattern" class="w-full h-full object-cover"/>
  </div>

  <div class="relative max-w-4xl mx-auto px-6 text-center">
    <!-- Titre -->
    <h2 class="text-5xl md:text-6xl font-serif font-light text-[#2a2a28] mb-6 leading-snug">
      We’re Here to Help
    </h2>

    <!-- Texte -->
    <p class="text-[#3a3a3a]/90 text-lg md:text-xl mb-8 leading-relaxed">
      Have a question, feedback, or want to collaborate? Send us a message and our team at 
      <span class="font-semibold text-[#6b4f3a]">Nostalgia Collective</span> 
      will get back to you as soon as possible.
    </p>

  
    <!-- Décor ligne vintage -->
    <div class="mt-10 flex justify-center">
      <div class="w-24 h-1 bg-[#6b4f3a] rounded-full"></div>
    </div>
  </div>
</section>



    <!-- ================= FORM + INFO ================= -->
    <section class="flex-grow py-24">
      <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12">

        <!-- Formulaire gauche -->
        <div class="bg-[#faf5ec] rounded-3xl shadow-lg p-12 border border-[#6b4f3a]">
          <h2 class="text-3xl md:text-4xl text-[#6b4f3a] font-serif font-light mb-8 text-center">
            Send Us a Message
          </h2>

          <form @submit.prevent="submitForm" class="space-y-6">

            <!-- Name -->
            <div>
              <label class="block text-sm font-medium mb-2 text-[#2a2a28]" for="name">Name</label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                placeholder="Your full name"
                required
                class="w-full px-4 py-3 rounded-xl border border-[#6b4f3a] focus:outline-none focus:ring-2 focus:ring-[#6b4f3a] bg-[#fffdf5] text-[#2a2a28]"
              />
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium mb-2 text-[#2a2a28]" for="email">Email</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                placeholder="your@email.com"
                required
                class="w-full px-4 py-3 rounded-xl border border-[#6b4f3a] focus:outline-none focus:ring-2 focus:ring-[#6b4f3a] bg-[#fffdf5] text-[#2a2a28]"
              />
            </div>

            <!-- Subject -->
            <div>
              <label class="block text-sm font-medium mb-2 text-[#2a2a28]" for="subject">Subject</label>
              <input
                type="text"
                id="subject"
                v-model="form.subject"
                placeholder="Subject of your message"
                required
                class="w-full px-4 py-3 rounded-xl border border-[#6b4f3a] focus:outline-none focus:ring-2 focus:ring-[#6b4f3a] bg-[#fffdf5] text-[#2a2a28]"
              />
            </div>

            <!-- Message -->
            <div>
              <label class="block text-sm font-medium mb-2 text-[#2a2a28]" for="message">Message</label>
              <textarea
                id="message"
                v-model="form.message"
                placeholder="Your message..."
                rows="6"
                required
                class="w-full px-4 py-3 rounded-xl border border-[#6b4f3a] focus:outline-none focus:ring-2 focus:ring-[#6b4f3a] bg-[#fffdf5] text-[#2a2a28]"
              ></textarea>
            </div>

            <!-- Submit -->
            <div class="text-center">
              <button
                type="submit"
                class="bg-[#6b4f3a] text-white px-10 py-3 uppercase tracking-wider rounded-xl hover:bg-[#fafaf0] hover:text-[#2a2a28] transition-all duration-300 shadow-md"
              >
                Send Message
              </button>
            </div>

            <!-- Status Message -->
            <p v-if="status.message" :class="status.success ? 'text-green-400' : 'text-red-500'" class="mt-4 text-center">
              {{ status.message }}
            </p>
          </form>
        </div>

        <!-- Informations contact droite -->
        <div class="bg-[#faf5ec] rounded-3xl shadow-lg p-12 border border-[#6b4f3a] flex flex-col justify-center space-y-6">
          <h2 class="text-3xl md:text-4xl text-[#6b4f3a] font-serif font-light mb-6 text-center md:text-left">
            Contact Information
          </h2>

          <div class="space-y-4 text-[#2a2a28] text-lg">
            <p class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-[#6b4f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3 7-3 7H3l3-7-3-7z"/>
              </svg>
              Phone: +212 600 123 456
            </p>
            <p class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-[#6b4f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"/>
              </svg>
              Email: contact@nostalgiacollective.com
            </p>
            <p class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-[#6b4f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l1.664-2.773A1 1 0 015.529 7h12.942a1 1 0 01.865 1.527L21 10v8a1 1 0 01-1 1H4a1 1 0 01-1-1v-8z"/>
              </svg>
              Address: 123 Vintage Street, Casablanca, Morocco
            </p>
            <p class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-[#6b4f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
              </svg>
              Hours: Mon-Fri 9:00am - 6:00pm
            </p>
          </div>

          <div class="mt-6 flex space-x-4 justify-center md:justify-start">
            <a href="#" class="text-[#6b4f3a] hover:text-[#fafaf0] transition-all duration-300">Facebook</a>
            <a href="#" class="text-[#6b4f3a] hover:text-[#fafaf0] transition-all duration-300">Instagram</a>
            <a href="#" class="text-[#6b4f3a] hover:text-[#fafaf0] transition-all duration-300">LinkedIn</a>
          </div>
        </div>

      </div>
    </section>
<!--  -->
<!-- ================= OUR VINTAGE INSPIRATIONS ================= -->
<section class="py-20 bg-[#faf5ec]">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <!-- Titre -->
    <h2 class="text-4xl md:text-5xl font-serif text-[#6b4f3a] mb-6">
      Our Vintage Inspirations
    </h2>
    <p class="text-[#2a2a28]/80 text-lg md:text-xl mb-12 max-w-3xl mx-auto">
      Explore some of the unique pieces that inspire our curated collection of vintage treasures.
    </p>

    <!-- Mini Galerie -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
      
      <!-- Item 1 -->
      <div class="relative group overflow-hidden rounded-xl shadow-lg border border-[#6b4f3a]">
        <img src="/images/inspo1.png" alt="Vintage Chair" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110"/>
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <p class="text-[#faf0e0] font-semibold text-center px-2">
            Vintage Chair 1950
          </p>
        </div>
      </div>

      <!-- Item 2 -->
      <div class="relative group overflow-hidden rounded-xl shadow-lg border border-[#6b4f3a]">
        <img src="/images/inspo2.jpeg" alt="Antique Lamp" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110"/>
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <p class="text-[#faf0e0] font-semibold text-center px-2">
            Antique Lamp 1920
          </p>
        </div>
      </div>

      <!-- Item 3 -->
      <div class="relative group overflow-hidden rounded-xl shadow-lg border border-[#6b4f3a]">
        <img src="/images/inspo3.jpg" alt="Retro Clock" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110"/>
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <p class="text-[#faf0e0] font-semibold text-center px-2">
            Retro Clock 1965
          </p>
        </div>
      </div>

      <!-- Item 4 -->
      <div class="relative group overflow-hidden rounded-xl shadow-lg border border-[#6b4f3a]">
        <img src="/images/about-style1.jpeg" alt="Vintage Radio" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110"/>
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <p class="text-[#faf0e0] font-semibold text-center px-2">
            Vintage Radio 1958
          </p>
        </div>
      </div>


    </div>

    <!-- CTA -->
    <div class="mt-12">
      <router-link
        to="/products"
        class="inline-block bg-[#6b4f3a] text-[#faf0e0] px-10 py-4 rounded-xl uppercase tracking-wider text-sm hover:bg-[#2a2a28] hover:text-[#fffaf0] transition-all duration-300 shadow-md"
      >
        Explore Full Collection
      </router-link>
    </div>
  </div>
</section>
<!--  -->
  <section class="py-24 bg-[#e6e7e4]">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <p class="uppercase tracking-[0.3em] text-sm text-gray-600 mb-4">
            Inspiration
          </p>
          <h3 class="text-4xl font-serif mb-6 text-black">
            #VintageFinds
          </h3>
          <p class="text-gray-700 mb-8 leading-relaxed">
            Une sélection de pièces uniques et intemporelles, pensées pour les amateurs
            de vintage authentique et d'élégance durable.
          </p>

          <a
            href="#"
            class="inline-block border border-black px-8 py-3 uppercase tracking-wider text-sm text-black hover:bg-black hover:text-white transition"
          >
            Follow Us
          </a>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <img src="/images/inspo1.png" class="h-40 object-cover w-full" />
          <img src="/images/inspo2.jpeg" class="h-40 object-cover w-full" />
          <img src="/images/inspo3.jpg" class="h-40 object-cover w-full" />
        </div>
      </div>
    </section>
    <!-- Footer -->
    <Footer />

  </div>
</template>

<script setup>
import { reactive } from 'vue'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
import apiClient from '@/api/axios'

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const status = reactive({
  success: false,
  message: ''
})

const submitForm = async () => {
  try {
    const response = await apiClient.post('/contact', { ...form })

    if (response.data.success) {
      status.success = true
      status.message = 'Message sent successfully!'
      form.name = ''
      form.email = ''
      form.subject = ''
      form.message = ''
    } else {
      status.success = false
      status.message = 'Failed to send message. Please try again.'
    }
  } catch (error) {
    status.success = false
    status.message = 'An error occurred. Please try again later.'
  }
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
