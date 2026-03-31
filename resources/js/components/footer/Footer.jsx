
import React from "react";
import { Mail, MapPin, Phone } from "lucide-react";
import { 
  FaFacebook, 
  FaTwitter, 
  FaInstagram, 
  FaYoutube, 
  FaLinkedin, 
  FaWhatsapp, 
  FaPinterest, 
  FaSnapchat, 
  FaReddit, 
  FaDiscord, 
  FaTelegram,
  FaGithub
} from "react-icons/fa";
import { BsTiktok, BsTwitterX } from "react-icons/bs";
import { Link, usePage } from "@inertiajs/react";

export default function Footer() {
  const { props } = usePage();
  const { logos } = usePage().props;
  const logo=logos?.logo || "";
  // console.log(logo);
  const { settings } = props;
  const siteName=settings?.site_name || "DANISH SOUVENIRS";
  const { footerInfo } = props;
  const {footer_social} = props;
  const {categoriess} = props;
  // const categories = categoriess?.name || [];
  // console.log(categoriess);
 //footer info
   const phone = footerInfo?.phone || "";
    const email = footerInfo?.email || "";
    const address = footerInfo?.address || "";
    const copyright = footerInfo?.copyright || "";


  // const categories = [
  //   {
  //     title: "Amalienborg Palace",
  //     imageText: "AMALIENBORG PALACE",
  //     imgSrc: "/categories/1.svg", // replace with real paths
  //   },
  //   {
  //     title: "Christiansborg Palace",
  //     imageText: "CHRISTIANSBORG PALACE",
  //     imgSrc: "/categories/2.svg",
  //   },
  //   {
  //     title: "Nyhavn",
  //     imageText: "NYHAVN",
  //     imgSrc: "/categories/3.svg",
  //   },
  //   {
  //     title: "Figurine",
  //     imageText: "figurine",
  //     imgSrc: "/categories/4.svg",
  //   },
  //   {
  //     title: "Keyring",
  //     imageText: "Keyring",
  //     imgSrc: "/categories/5.svg",
  //   },
  //   {
  //     title: "Dyhavn",
  //     imageText: "dyhavn",
  //     imgSrc: "/categories/6.svg",
  //   },
  //   {
  //     title: "Scandinavia",
  //     imageText: "Scandinavia",
  //     imgSrc: "/categories/7.svg",
  //   },
  // ];

  const customerPages = [
    {
      title: "Help & Support",
      url: route("support.center"),
    },
    {
      title: "How to Order",
      url: route("how.to.order"),
    },
    {
      title: "Privacy Policy",
      url: route("privacy.policy"),
    },
    {
      title: "Return Policy",
      url: route("return.policy"),
    },
    {
      title: "Shipping",
      url: route("shipping.delivery"),
    },
    {
      title: "Legal Notice",
      url: route("legal.policy"),
    },
  ];

  const getIcon = (iconName) => {
    if (!iconName) return null;
    const name = iconName.toLowerCase();

    if (name.includes('facebook')) return <FaFacebook />;
    if (name.includes('twitter-x') || name.includes('x-twitter')) return <BsTwitterX />;
    if (name.includes('twitter')) return <FaTwitter />;
    if (name.includes('instagram')) return <FaInstagram />;
    if (name.includes('youtube')) return <FaYoutube />;
    if (name.includes('tiktok')) return <BsTiktok />;
    if (name.includes('linkedin')) return <FaLinkedin />;
    if (name.includes('whatsapp')) return <FaWhatsapp />;
    if (name.includes('pinterest')) return <FaPinterest />;
    if (name.includes('snapchat')) return <FaSnapchat />;
    if (name.includes('reddit')) return <FaReddit />;
    if (name.includes('discord')) return <FaDiscord />;
    if (name.includes('telegram')) return <FaTelegram />;
    if (name.includes('github')) return <FaGithub />;

    return <i className={iconName}></i>;
  };
  
  return (
    <footer className=" text-white">
      <div className="bg-red">
        <div className="container px-6 py-12 md:py-16">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-8">
            {/* Column 1 - Brand & Contact */}
            <div>
              {footerInfo?.logo ? (
                <img 
                  src={`/`+footerInfo.logo}
                  alt={siteName}
                  className="h-12 md:h-16 w-auto mb-4 object-contain"
                />
              ) : (
                <h3 className="text-xl md:text-2xl font-bold mb-4 tracking-wide">
                  {siteName}
                </h3>
              )}
              {/* <p className="text-red-100/90 mb-5 text-sm md:text-base leading-relaxed">
                Your premier destination for quality Bags and T-shirts. We
                deliver excellence in every product.
              </p> */}

              <div className="space-y-3 text-sm">
                <p className="flex items-center gap-2">
                  <span>
                    <Phone size={18} />
                  </span>
                      {phone}
                </p>
                <p className="flex items-center gap-2">
                  <span>
                    <Mail size={18} />
                  </span>
                      {email}
                </p>
                <p className="flex items-center gap-2">
                  <span>
                    <MapPin size={18} />
                  </span>{" "}
                  {address}
                </p>
              </div>

              <div className="mt-6">
                <p className="text-sm mb-3">Follow US</p>
                <div className="flex gap-4">
                  {footer_social && footer_social.map((social, index) => (
                    <Link 
                      key={index} 
                      href={social.url} 
                      className="hover:text-red-200 transition-colors text-xl"
                      target="_blank"
                      rel="noopener noreferrer"
                      title={social.name}
                    >
                      {getIcon(social.icon)}
                    </Link>
                  ))}
                </div>
              </div>
            </div>

            {/* Column 2 - Quick Links */}
            <div>
              <h4 className="text-lg font-semibold mb-5">Quick Links</h4>
              <ul className="space-y-2.5 text-red-100/90 text-sm">
                <li>
                  <Link href={route("home")} className="hover:text-white transition-colors">
                    Home
                  </Link>
                </li>
                <li>
                  <Link href={route("about")} className="hover:text-white transition-colors">
                    About
                  </Link>
                </li>
                <li>
                  <Link
                    href={route("all.products")}
                    className="hover:text-white transition-colors"
                  >
                    Shop
                  </Link>
                </li>
                <li>
                  <Link
                    href={route("campaign.index")}
                    className="hover:text-white transition-colors"
                  >
                    Campaigns
                  </Link>
                </li>
                {/* <li>
                  <a href="#" className="hover:text-white transition-colors">
                    About Us
                  </a>
                </li> */}
                <li>
                  <Link
                    href={route("contact")}
                    className="hover:text-white transition-colors"
                  >
                    Contact
                  </Link>
                </li>
                <li>
                  <a
                    href="https://b2bviking.com/"
                    className="hover:text-white transition-colors" target="_blank"
                  >
                    Bussiness with us <br/> b2bviking
                  </a>
                </li>
              </ul>
            </div>

            {/* Column 3 - Categories */}
            <div>
              <h4 className="text-lg font-semibold mb-5">Categories</h4>
              <ul className="space-y-2.5 text-red-100/90 text-sm">
                {(categoriess || []).map((category, index) => (
                  <li key={index}>
                    <Link href={route('category.products', category.slug)} className="hover:text-white transition-colors">
                      {category.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Column 4 - Customer Service + Newsletter */}
            <div>
              <h4 className="text-lg font-semibold mb-5">Customer Service</h4>
              <ul className="space-y-2.5 text-red-100/90 text-sm mb-8">
                {customerPages.map((page, index) => (
                  <li key={index}>
                    <Link
                      href={page.url}
                      className="hover:text-white transition-colors"
                    >
                      {page.title}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          </div>

          <div className="relative flex items-center  mt-8">
            {/* Top thin line */}
            <div className="w-full   h-px bg-white"></div>

            {/* Main content */}
            <div className="w-50 h-[60] items-center gap-3 md:gap-4 shrink-0     px-4 py-2">
              <img
                src={`/`+logo}
                alt="Logo"
                className="w-full h-full object-center"
              />
            </div>

            {/* Bottom thin line */}
            <div className="w-full h-px bg-white"></div>
          </div>
        </div>
      </div>

      {/* Bottom Bar - Payment Methods */}
      <div className="bg-red-800">
        <div className="container">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-2 content-center items-center py-6  text-sm md:text-base">
            <div className="text-center md:text-left ">
              <div>Ideation & Design Shahadat</div>
            </div>
            <div className="flex justify-center">
              <h4>
                ©{new Date().getFullYear()} {copyright}
              </h4>
            </div>
            <div className="flex justify-center md:justify-end">
              <Link href="https://inoodex.com/">
                <h3>Develope By Inoodex</h3>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}



