<template>
  <div class="bg-[#faf9f5] text-[#2a2a28] font-serif">

    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="relative h-[70vh] md:h-[80vh]">
      <img
        src="/images/about-hero.jpeg"
        alt="About Us"
        class="absolute inset-0 w-full h-full object-cover brightness-75"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#faf9f5]/90"></div>
      <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-6">
        <p class="uppercase tracking-widest text-sm text-[#d4af37]/80 mb-4">
          Our Story
        </p>
        <h1 class="text-5xl md:text-6xl lg:text-7xl text-[#2a2a28] font-light drop-shadow-md">
          Nostalgia Collective
        </h1>
        <p class="mt-4 text-[#6b4f3a]/70 italic text-base md:text-lg max-w-2xl">
          Celebrating timeless design, memories, and vintage treasures.
        </p>
      </div>
    </section>

    <!-- ================= INTRODUCTION ================= -->
    <section class="py-24">
      <div class="max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-light text-[#2a2a28] mb-6">
          Why Nostalgia Collective?
        </h2>
        <p class="text-gray-700 text-lg md:text-xl leading-relaxed max-w-3xl mx-auto">
          Nostalgia Collective was founded to preserve the beauty and stories behind vintage pieces. 
          We connect collectors and enthusiasts with handpicked treasures that embody timeless charm, 
          authenticity, and sustainability.
        </p>
      </div>
    </section>

    <!-- ================= OUR PROCESS ================= -->
    <section class="py-24 bg-[#2a2a28] text-[#faf9f5]">
      <div class="max-w-7xl mx-auto px-6 text-center mb-20">
        <p class="uppercase tracking-widest text-sm text-[#d4af37]/80 mb-4">
          Our Process
        </p>
        <h2 class="text-4xl md:text-5xl font-light">
          How It Works
        </h2>
      </div>

      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-14">

        <!-- Step 1 -->
        <div class="text-center">
          <img
            src="/images/inspo1.png"
            alt="Sourcing"
            class="w-full h-[380px] object-cover mb-6 rounded-lg shadow-lg"
          />
          <h3 class="uppercase tracking-widest text-sm text-[#d4af37] mb-4">
            Step One
          </h3>
          <p class="text-[#fdf6ec]/80 leading-relaxed">
            We carefully source vintage items from trusted collectors,
            artisans, and timeless archives.
          </p>
        </div>

        <!-- Step 2 -->
        <div class="text-center">
          <img
            src="/images/about-hero.jpeg"
            alt="Curation"
            class="w-full h-[380px] object-cover mb-6 rounded-lg shadow-lg"
          />
          <h3 class="uppercase tracking-widest text-sm text-[#d4af37] mb-4">
            Step Two
          </h3>
          <p class="text-[#fdf6ec]/80 leading-relaxed">
            Each piece is authenticated, curated, and documented
            with care and attention to detail.
          </p>
        </div>

        <!-- Step 3 -->
        <div class="text-center">
          <img
            src="/images/inspo3.jpg"
            alt="Delivery"
            class="w-full h-[380px] object-cover mb-6 rounded-lg shadow-lg"
          />
          <h3 class="uppercase tracking-widest text-sm text-[#d4af37] mb-4">
            Step Three
          </h3>
          <p class="text-[#fdf6ec]/80 leading-relaxed">
            Timeless treasures find a new home and continue their story
            through generations.
          </p>
        </div>

      </div>
    </section>

    <!-- ================= IMAGE + TEXT SECTION 1 ================= -->
    <section class="py-24 bg-[#f5f1ec]">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        <div class="overflow-hidden rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
          <img src="/images/inspo1.png" alt="Curated Vintage" class="w-full h-[500px] object-cover"/>
        </div>
        <div class="space-y-6">
          <h3 class="text-3xl md:text-4xl text-[#2a2a28] font-light">
            Curated Vintage Treasures
          </h3>
          <p class="text-[#6b4f3a]/80 leading-relaxed text-lg">
            Every item in our marketplace is carefully handpicked, authenticated, 
            and presented with care. We believe in objects that tell a story.
          </p>
        </div>
      </div>
    </section>

    <!-- ================= IMAGE + TEXT SECTION 2 ================= -->
    <section class="py-24">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
          <h3 class="text-3xl md:text-4xl text-[#2a2a28] font-light">
            Built by Collectors, for Collectors
          </h3>
          <p class="text-[#6b4f3a]/80 leading-relaxed text-lg">
            Our community brings together vintage enthusiasts and collectors 
            who share a passion for timeless design.
          </p>
        </div>
        <div class="overflow-hidden rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
          <img src="/images/inspo2.jpeg" alt="Community Vintage" class="w-full h-[500px] object-cover"/>
        </div>
      </div>
    </section>

    <!-- ================= VALUES ================= -->
    <section class="py-24 bg-[#fdf6ec]">
      <div class="max-w-7xl mx-auto px-6 text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-light text-[#2a2a28]">
          Our Core Values
        </h2>
      </div>

      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-12">

        <!-- Authenticity -->
        <div class="bg-[#faf9f5] p-8 rounded-2xl shadow-lg border border-[#d4af37]/30">
          <h4 class="text-xl mb-3 font-semibold">Authenticity</h4>
          <p class="text-[#6b4f3a]/80">
            Every piece is verified to guarantee genuine vintage quality.
          </p>
        </div>

        <!-- Curation -->
        <div class="bg-[#faf9f5] p-8 rounded-2xl shadow-lg border border-[#d4af37]/30">
          <h4 class="text-xl mb-3 font-semibold">Curation</h4>
          <p class="text-[#6b4f3a]/80">
            Each item is handpicked for character and timeless charm.
          </p>
        </div>

        <!-- Sustainability -->
        <div class="bg-[#faf9f5] p-8 rounded-2xl shadow-lg border border-[#d4af37]/30">
          <h4 class="text-xl mb-3 font-semibold">Sustainability</h4>
          <p class="text-[#6b4f3a]/80">
            Giving vintage pieces a second life supports a circular economy.
          </p>
        </div>

      </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="py-24 bg-gradient-to-r from-[#fdf6ec] to-[#faf0e0] text-center">
      <h2 class="text-4xl md:text-5xl font-light mb-8">
        Begin Your <span class="italic">Vintage Journey</span>
      </h2>
      <p class="text-[#6b4f3a]/80 text-lg mb-10 max-w-2xl mx-auto">
        Discover unique pieces and celebrate timeless design.
      </p>
      <div class="flex justify-center gap-4">
        <router-link to="/products" class="bg-[#6b4f3a] text-white px-10 py-4 uppercase tracking-wider text-sm rounded-lg">
          Shop Collection
        </router-link>
        <router-link to="/contact" class="border border-[#6b4f3a] px-10 py-4 uppercase tracking-wider text-sm rounded-lg">
          Get In Touch
        </router-link>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
</script>
