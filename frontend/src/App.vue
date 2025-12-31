<template>
  <div class="min-h-screen bg-white">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-16 items-center">

        <!-- Left -->
        <div class="space-y-8">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-full">
            <Leaf class="w-4 h-4 text-green-600" />
            <span class="text-sm text-green-700">Mode Durable & Responsable</span>
          </div>

          <h1 class="text-6xl leading-tight">
            <span class="block text-gray-900">L'histoire</span>
            <span class="block bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 bg-clip-text text-transparent">
              d'Artifact
            </span>
          </h1>

          <p class="text-xl text-gray-600">
            Une plateforme e-commerce dédiée à la vente d'objets vintage qui promeut
            la mode durable et responsable.
          </p>

          <div class="flex gap-4">
            <button
              @click="navigate('gallery')"
              class="px-8 py-4 bg-gray-900 text-white rounded-2xl flex items-center gap-2 hover:shadow-xl"
            >
              Explorer la boutique
              <ArrowRight class="w-5 h-5" />
            </button>

            <button
              @click="navigate('contact')"
              class="px-8 py-4 border-2 border-gray-900 rounded-2xl hover:bg-gray-900 hover:text-white"
            >
              Nous contacter
            </button>
          </div>
        </div>

        <!-- Right images -->
        <div class="grid grid-cols-2 gap-4">
          <img
            v-for="(img, i) in images"
            :key="i"
            :src="img"
            class="rounded-3xl shadow-xl object-cover hover:scale-105 transition"
          />
        </div>

      </div>
    </section>

    <!-- STATS -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-6">
        <div
          v-for="stat in stats"
          :key="stat.label"
          class="bg-white p-8 rounded-3xl shadow hover:shadow-xl"
        >
          <component
            :is="stat.icon"
            class="w-8 h-8 text-white p-2 rounded-xl mb-4"
            :class="stat.bg"
          />
          <div class="text-3xl">{{ stat.value }}</div>
          <div class="text-gray-600">{{ stat.label }}</div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-5xl text-center mb-16">Pourquoi choisir Artifact ?</h2>

        <div class="grid md:grid-cols-2 gap-8">
          <div
            v-for="feature in features"
            :key="feature.title"
            class="p-10 bg-white border rounded-3xl hover:shadow-xl"
          >
            <component
              :is="feature.icon"
              class="w-10 h-10 text-white p-2 rounded-xl mb-6"
              :class="feature.bg"
            />
            <h3 class="text-2xl mb-3">{{ feature.title }}</h3>
            <p class="text-gray-600">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import {
  Leaf,
  Users,
  Package,
  Heart,
  Star,
  ArrowRight,
  Shield,
  Award
} from 'lucide-vue-next'

const emit = defineEmits(['navigate'])

const navigate = (page) => {
  emit('navigate', page)
}

const images = [
  'https://images.unsplash.com/photo-1551607939-46fc8ac00815',
  'https://images.unsplash.com/photo-1677753727712-c79ce4c420c1',
  'https://images.unsplash.com/photo-1728241965139-cf300d490f7e',
  'https://images.unsplash.com/photo-1753162658596-2ccba5e4246a'
]

const stats = [
  { label: 'Vendeurs actifs', value: '100+', icon: Users, bg: 'bg-blue-500' },
  { label: 'Produits vintage', value: '500+', icon: Package, bg: 'bg-purple-500' },
  { label: 'Clients heureux', value: '2k+', icon: Heart, bg: 'bg-pink-500' },
  { label: 'Satisfaction', value: '98%', icon: Star, bg: 'bg-amber-500' }
]

const features = [
  {
    icon: Leaf,
    title: 'Éco-responsable',
    description: 'Une seconde vie pour la mode vintage.',
    bg: 'bg-green-500'
  },
  {
    icon: Shield,
    title: 'Authentique',
    description: 'Produits vérifiés et certifiés.',
    bg: 'bg-blue-500'
  },
  {
    icon: Users,
    title: 'Communauté',
    description: 'Passionnés de mode durable.',
    bg: 'bg-purple-500'
  },
  {
    icon: Award,
    title: 'Qualité Premium',
    description: 'Sélection exigeante des vendeurs.',
    bg: 'bg-orange-500'
  }
]
</script>
