import { defineCollection, z } from 'astro:content';

const insights = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    category: z.string(),
    type: z.enum(['Article', 'Video', 'Podcast', 'Report']),
    date: z.string(),
    image: z.string().optional(),
    featured: z.boolean().default(false),
    excerpt: z.string().optional(),
  }),
});

export const collections = { insights };
